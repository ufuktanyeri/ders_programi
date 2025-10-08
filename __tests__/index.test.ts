import { BlackboxProvider } from '../src/index';
import axios from 'axios';

// Mock axios
jest.mock('axios');
const mockedAxios = axios as jest.Mocked<typeof axios>;
const mockCreate = jest.fn();
mockedAxios.create.mockReturnValue({
  post: jest.fn(),
  get: jest.fn(),
  interceptors: {
    request: {
      use: jest.fn()
    },
    response: {
      use: jest.fn()
    }
  }
} as any);

describe('BlackboxProvider', () => {
  let provider: BlackboxProvider;
  const mockApiKey = 'test-api-key';

  beforeEach(() => {
    jest.clearAllMocks();
    provider = new BlackboxProvider({ apiKey: mockApiKey });
  });

  describe('constructor', () => {
    it('should create provider with required options', () => {
      expect(provider).toBeInstanceOf(BlackboxProvider);
      expect(mockedAxios.create).toHaveBeenCalledWith({
        baseURL: 'https://api.blackbox.ai',
        timeout: 30000,
        headers: {
          'Authorization': `Bearer ${mockApiKey}`,
          'Content-Type': 'application/json',
          'User-Agent': 'blackbox-ai-provider/1.0.0'
        }
      });
    });

    it('should throw error if API key is missing', () => {
      expect(() => new BlackboxProvider({ apiKey: '' })).toThrow('API key is required');
    });

    it('should use custom options', () => {
      const customProvider = new BlackboxProvider({
        apiKey: mockApiKey,
        baseUrl: 'https://custom.api.com',
        timeout: 60000
      });

      expect(customProvider).toBeInstanceOf(BlackboxProvider);
    });
  });

  describe('chat method', () => {
    it('should make chat completion request', async () => {
      const mockResponse = {
        data: {
          id: 'test-id',
          object: 'chat.completion',
          created: 1234567890,
          model: 'blackboxai',
          choices: [
            {
              index: 0,
              message: { role: 'assistant', content: 'Test response' },
              finish_reason: 'stop'
            }
          ],
          usage: {
            prompt_tokens: 10,
            completion_tokens: 5,
            total_tokens: 15
          }
        }
      };

      const mockClient = {
        post: jest.fn().mockResolvedValue(mockResponse),
        get: jest.fn(),
        interceptors: {
          request: { use: jest.fn() },
          response: { use: jest.fn() }
        }
      };

      mockedAxios.create.mockReturnValue(mockClient as any);
      
      const newProvider = new BlackboxProvider({ apiKey: mockApiKey });
      
      const request = {
        model: 'blackboxai',
        messages: [{ role: 'user' as const, content: 'Test message' }]
      };

      const response = await newProvider.chat(request);

      expect(mockClient.post).toHaveBeenCalledWith('/v1/chat/completions', {
        ...request,
        stream: false
      });
      expect(response).toEqual(mockResponse.data);
    });

    it('should throw error for streaming requests', async () => {
      const request = {
        model: 'blackboxai',
        messages: [{ role: 'user' as const, content: 'Test message' }],
        stream: true
      };

      await expect(provider.chat(request)).rejects.toThrow('Use stream() method for streaming requests');
    });
  });

  describe('listModels method', () => {
    it('should fetch available models', async () => {
      const mockModelsResponse = {
        data: {
          data: [
            { id: 'blackboxai', object: 'model', created: 1234567890, owned_by: 'blackbox' },
            { id: 'gpt-3.5-turbo', object: 'model', created: 1234567890, owned_by: 'openai' }
          ]
        }
      };

      const mockClient = {
        post: jest.fn(),
        get: jest.fn().mockResolvedValue(mockModelsResponse),
        interceptors: {
          request: { use: jest.fn() },
          response: { use: jest.fn() }
        }
      };

      mockedAxios.create.mockReturnValue(mockClient as any);
      
      const newProvider = new BlackboxProvider({ apiKey: mockApiKey });
      
      const models = await newProvider.listModels();

      expect(mockClient.get).toHaveBeenCalledWith('/v1/models');
      expect(models).toEqual(mockModelsResponse.data);
    });
  });
});
