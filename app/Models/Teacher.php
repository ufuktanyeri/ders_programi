<?php

namespace App\Models;

use Model;

/**
 * Teacher Model
 * 
 * Represents teaching staff (Ogretim Elemanlari)
 */
class Teacher extends Model
{
  /**
   * Table name
   * @var string
   */
  protected $table = 'ogretim_elemanlari';

  /**
   * Primary key
   * @var string
   */
  protected $primaryKey = 'ogretmen_id';

  /**
   * Fillable columns
   * @var array
   */
  protected $fillable = [
    'ad_soyad',
    'unvan',
    'email',
    'telefon',
    'ofis',
    'aktif',
    'renk_kodu'
  ];

  /**
   * Timestamps
   * @var bool
   */
  protected $timestamps = true;

  /**
   * Get active teachers
   * 
   * @return array
   */
  public function getActiveTeachers(): array
  {
    return $this->findAll(['aktif' => true], 'ad_soyad ASC');
  }

  /**
   * Get teacher by email
   * 
   * @param string $email Email address
   * @return array|null
   */
  public function getByEmail(string $email): ?array
  {
    return $this->findOne(['email' => $email]);
  }

  /**
   * Get teacher with schedule
   * 
   * @param int $teacherId Teacher ID
   * @return array|null
   */
  public function getWithSchedule(int $teacherId): ?array
  {
    $sql = "
            SELECT t.*, 
                   COUNT(DISTINCT da.ders_id) as course_count,
                   COUNT(DISTINCT hp.program_id) as schedule_count
            FROM ogretim_elemanlari t
            LEFT JOIN ders_atamalari da ON t.ogretmen_id = da.ogretmen_id
            LEFT JOIN haftalik_program hp ON da.atama_id = hp.atama_id
            WHERE t.ogretmen_id = :id
            GROUP BY t.ogretmen_id
        ";

    $result = $this->query($sql, ['id' => $teacherId]);
    return $result[0] ?? null;
  }

  /**
   * Get teacher's workload
   * 
   * @param int $teacherId Teacher ID
   * @return array
   */
  public function getWorkload(int $teacherId): array
  {
    $sql = "
            SELECT 
                t.ad_soyad,
                COUNT(DISTINCT da.ders_id) as total_courses,
                SUM(d.teorik) as total_theory_hours,
                SUM(d.uygulama) as total_practice_hours,
                (SUM(d.teorik) + SUM(d.uygulama)) as total_hours
            FROM ogretim_elemanlari t
            LEFT JOIN ders_atamalari da ON t.ogretmen_id = da.ogretmen_id
            LEFT JOIN dersler d ON da.ders_id = d.ders_id
            WHERE t.ogretmen_id = :id
            GROUP BY t.ogretmen_id
        ";

    $result = $this->query($sql, ['id' => $teacherId]);
    return $result[0] ?? [
      'ad_soyad' => '',
      'total_courses' => 0,
      'total_theory_hours' => 0,
      'total_practice_hours' => 0,
      'total_hours' => 0
    ];
  }

  /**
   * Check for schedule conflicts
   * 
   * @param int $teacherId Teacher ID
   * @param string $day Day of week
   * @param string $startTime Start time
   * @param string $endTime End time
   * @return bool
   */
  public function hasConflict(int $teacherId, string $day, string $startTime, string $endTime): bool
  {
    $sql = "
            SELECT COUNT(*) FROM haftalik_program hp
            JOIN ders_atamalari da ON hp.atama_id = da.atama_id
            WHERE da.ogretmen_id = :teacher_id 
            AND hp.gun = :day
            AND hp.baslangic_saat < :end_time 
            AND hp.bitis_saat > :start_time
        ";

    $result = $this->query($sql, [
      'teacher_id' => $teacherId,
      'day' => $day,
      'start_time' => $startTime,
      'end_time' => $endTime
    ]);

    return ($result[0]['COUNT(*)'] ?? 0) > 0;
  }

  /**
   * Search teachers
   * 
   * @param string $query Search query
   * @return array
   */
  public function search(string $query): array
  {
    $sql = "
            SELECT * FROM ogretim_elemanlari
            WHERE (ad_soyad LIKE :query OR email LIKE :query OR unvan LIKE :query)
            AND aktif = 1
            ORDER BY ad_soyad
        ";

    return $this->query($sql, ['query' => "%{$query}%"]);
  }
}
