import axios, { AxiosInstance, AxiosError } from 'axios';
import { EventEmitter } from 'eventemitter3';
import {
  BlackboxProviderOptions,
  ChatCompletionRequest,
  ChatCompletionResponse,
  ChatCompletionStreamResponse,
  BlackboxError
} from '../types/index.js';

export class BlackboxProvider extends EventEmitter {
  private apiKey: string;
  private baseUrl: string;
  private timeout: number;
  private client: AxiosInstance;

  constructor(options: BlackboxProviderOptions) {
    super();
    
    if (!options.apiKey) {
      throw new Error('API key is required');
    }

    this.apiKey = options.apiKey;
    this.baseUrl = options.baseUrl || 'https://api.blackbox.ai';
    this.timeout = options.timeout || 30000;

    this.client = axios.create({
      baseURL: this.baseUrl,
      timeout: this.timeout,
      headers: {
        'Authorization': `Bearer ${this.apiKey}`,
        'Content-Type': 'application/json',
        'User-Agent': 'blackbox-ai-provider/1.0.0'
      }
    });

    this.setupInterceptors();
  }

  private setupInterceptors(): void {
    this.client.interceptors.request.use(
      (config) => {
        this.emit('request', config);
        return config;
      },
      (error) => {
        this.emit('error', error);
        return Promise.reject(error);
      }
    );

    this.client.interceptors.response.use(
      (response) => {
        this.emit('response', response);
        return response;
      },
      (error: AxiosError<BlackboxError>) => {
        this.emit('error', error);
        
        if (error.response?.data?.error) {
          const customError = new Error(error.response.data.error.message);
          customError.name = error.response.data.error.type;
          throw customError;
        }
        
        throw error;
      }
    );
  }

  /**
   * Chat completion API
   */
  async chat(request: ChatCompletionRequest): Promise<ChatCompletionResponse> {
    if (request.stream) {
      throw new Error('Use stream() method for streaming requests');
    }

    try {
      const response = await this.client.post('/v1/chat/completions', {
        ...request,
        stream: false
      });

      return response.data;
    } catch (error) {
      this.handleError(error);
      throw error;
    }
  }

  /**
   * Streaming chat completion
   */
  async* stream(request: ChatCompletionRequest): AsyncGenerator<ChatCompletionStreamResponse> {
    try {
      const response = await this.client.post('/v1/chat/completions', {
        ...request,
        stream: true
      }, {
        responseType: 'stream'
      });

      const stream = response.data;
      let buffer = '';

      for await (const chunk of stream) {
        buffer += chunk.toString();
        
        const lines = buffer.split('\n');
        buffer = lines.pop() || '';

        for (const line of lines) {
          if (line.trim() === '') continue;
          if (line.startsWith('data: ')) {
            const data = line.slice(6);
            
            if (data === '[DONE]') {
              return;
            }

            try {
              const parsed: ChatCompletionStreamResponse = JSON.parse(data);
              yield parsed;
            } catch (parseError) {
              console.warn('Failed to parse streaming response:', parseError);
            }
          }
        }
      }
    } catch (error) {
      this.handleError(error);
      throw error;
    }
  }

  /**
   * List available models
   */
  async listModels(): Promise<{ data: Array<{ id: string; object: string; created: number; owned_by: string }> }> {
    try {
      const response = await this.client.get('/v1/models');
      return response.data;
    } catch (error) {
      this.handleError(error);
      throw error;
    }
  }

  private handleError(error: any): void {
    if (axios.isAxiosError(error)) {
      if (error.response) {
        console.error(`Blackbox API Error: ${error.response.status} - ${error.response.statusText}`);
        console.error('Response data:', error.response.data);
      } else if (error.request) {
        console.error('Network Error: No response received from Blackbox API');
      } else {
        console.error('Request Error:', error.message);
      }
    } else {
      console.error('Unknown Error:', error);
    }
  }
}

export default BlackboxProvider;
