<?php
$content = ob_start(); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-gradient mb-3">Yardım</h1>
                <p class="lead text-muted">Sıkça sorulan sorular ve kullanım kılavuzu</p>
            </div>

            <!-- FAQ Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-question-circle text-primary me-2"></i>
                        Sıkça Sorulan Sorular
                    </h3>

                    <div class="accordion" id="faqAccordion">
                        <!-- FAQ 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    Sisteme nasıl giriş yapabilirim?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" 
                                 aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Sisteme giriş yapmak için Google hesabınızı kullanabilirsiniz. 
                                    Ana sayfadaki "Giriş Yap" butonuna tıklayarak Google OAuth ile güvenli 
                                    şekilde kimlik doğrulama yapabilirsiniz. Kurumsal @ankara.edu.tr e-posta 
                                    adresinizle giriş yapmanız önerilir.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                    Ders programımı nasıl görüntüleyebilirim?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" 
                                 aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    "Ders Programları" menüsünden programınızı seçerek haftalık ders programınızı 
                                    görüntüleyebilirsiniz. Program, günlere ve saatlere göre düzenlenmiş şekilde 
                                    gösterilir. İsterseniz PDF formatında indirebilirsiniz.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    Sisteme öğretim elemanı olarak nasıl erişebilirim?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" 
                                 aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Öğretim elemanları için özel yetkilendirme sistemi mevcuttur. 
                                    İlk girişinizde kullanıcı onay sistemi üzerinden yetkilendirilme talebiniz 
                                    oluşturulacaktır. Sistem yöneticisi tarafından onaylandıktan sonra 
                                    öğretim elemanı yetkilerinize erişebilirsiniz.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                    Ders çakışması durumunda ne yapmalıyım?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" 
                                 aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Sistem otomatik olarak ders çakışmalarını kontrol eder ve uyarı verir. 
                                    Eğer bir çakışma tespit ederseniz, bölüm sekreterliğine veya sistem 
                                    yöneticisine bildirmeniz gerekmektedir. Admin kullanıcılar çakışmaları 
                                    düzeltme yetkisine sahiptir.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 5 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                    Mobil cihazlardan erişebilir miyim?
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" 
                                 aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Evet, sistem tamamen responsive tasarıma sahiptir. Akıllı telefon, 
                                    tablet veya bilgisayardan rahatlıkla erişebilirsiniz. Tüm özellikler 
                                    mobil cihazlarda da sorunsuz çalışır.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Roles -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-users text-success me-2"></i>
                        Kullanıcı Rolleri
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th>Yetkiler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-danger">Admin</span></td>
                                    <td>Tüm sisteme tam erişim, program oluşturma ve düzenleme</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">Öğretim Elemanı</span></td>
                                    <td>Kendi derslerini görüntüleme ve düzenleme</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">Instructor</span></td>
                                    <td>Sınırlı düzenleme yetkileri</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-secondary">Misafir</span></td>
                                    <td>Sadece programları görüntüleme</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Guide -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-book text-info me-2"></i>
                        Hızlı Başlangıç Kılavuzu
                    </h3>
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item">
                            <strong>Giriş Yapın:</strong> Google hesabınızla sisteme giriş yapın
                        </li>
                        <li class="list-group-item">
                            <strong>Programınızı Seçin:</strong> Ana menüden ilgili programı bulun
                        </li>
                        <li class="list-group-item">
                            <strong>Ders Programını Görüntüleyin:</strong> Haftalık programınızı inceleyin
                        </li>
                        <li class="list-group-item">
                            <strong>İndir veya Paylaş:</strong> PDF olarak kaydedin veya paylaşın
                        </li>
                    </ol>
                </div>
            </div>

            <!-- Contact for Support -->
            <div class="alert alert-info mt-4" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Yardım mı gerekiyor?</strong> 
                Daha fazla bilgi için <a href="/contact" class="alert-link">İletişim</a> sayfasını ziyaret edebilirsiniz.
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
