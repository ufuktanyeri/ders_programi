<?php

namespace App\Models;

use Model;

/**
 * Program Model
 * 
 * Represents educational programs (Associate Degree Programs)
 */
class Program extends Model
{
  /**
   * Table name
   * @var string
   */
  protected $table = 'programlar';

  /**
   * Primary key
   * @var string
   */
  protected $primaryKey = 'program_id';

  /**
   * Fillable columns
   * @var array
   */
  protected $fillable = [
    'program_kodu',
    'program_adi',
    'program_adi_en',
    'sure',
    'aktif'
  ];

  /**
   * Timestamps
   * @var bool
   */
  protected $timestamps = true;

  /**
   * Get active programs
   * 
   * @return array
   */
  public function getActivePrograms(): array
  {
    return $this->findAll(['aktif' => true], 'program_kodu ASC');
  }

  /**
   * Get program by code
   * 
   * @param string $code Program code
   * @return array|null
   */
  public function getByCode(string $code): ?array
  {
    return $this->findOne(['program_kodu' => $code]);
  }

  /**
   * Get program with courses
   * 
   * @param int $programId Program ID
   * @return array|null
   */
  public function getWithCourses(int $programId): ?array
  {
    $sql = "
            SELECT p.*, 
                   COUNT(pd.ders_id) as course_count
            FROM programlar p
            LEFT JOIN program_dersleri pd ON p.program_id = pd.program_id
            WHERE p.program_id = :id
            GROUP BY p.program_id
        ";

    $result = $this->query($sql, ['id' => $programId]);
    return $result[0] ?? null;
  }

  /**
   * Get program statistics
   * 
   * @param int $programId Program ID
   * @return array
   */
  public function getStatistics(int $programId): array
  {
    $sql = "
            SELECT 
                COUNT(DISTINCT pd.ders_id) as total_courses,
                COUNT(DISTINCT da.ogretmen_id) as total_teachers,
                COUNT(DISTINCT hp.program_id) as scheduled_courses
            FROM programlar p
            LEFT JOIN program_dersleri pd ON p.program_id = pd.program_id
            LEFT JOIN ders_atamalari da ON pd.ders_id = da.ders_id AND da.program_id = p.program_id
            LEFT JOIN haftalik_program hp ON da.atama_id = hp.atama_id
            WHERE p.program_id = :id
        ";

    $result = $this->query($sql, ['id' => $programId]);
    return $result[0] ?? [
      'total_courses' => 0,
      'total_teachers' => 0,
      'scheduled_courses' => 0
    ];
  }

  /**
   * Activate program
   * 
   * @param int $programId Program ID
   * @return bool
   */
  public function activate(int $programId): bool
  {
    return $this->update($programId, ['aktif' => true]);
  }

  /**
   * Deactivate program
   * 
   * @param int $programId Program ID
   * @return bool
   */
  public function deactivate(int $programId): bool
  {
    return $this->update($programId, ['aktif' => false]);
  }

  /**
   * Search programs
   * 
   * @param string $query Search query
   * @return array
   */
  public function search(string $query): array
  {
    $sql = "
            SELECT * FROM programlar
            WHERE (program_kodu LIKE :query OR program_adi LIKE :query OR program_adi_en LIKE :query)
            AND aktif = 1
            ORDER BY program_kodu
        ";

    return $this->query($sql, ['query' => "%{$query}%"]);
  }
}
