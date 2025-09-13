<?php
// app/views/partials/modals/qr_modals.php
// This file will be dynamically included in the cards view when needed
?>

<?php if (isset($mka_data) && $mka_data): ?>
    <!-- QR Modal MKA -->
    <div class="modal fade" id="qrMkaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kontrola biletu - MKA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="<?php echo $cardController->generateQRCode('MKA:' . $mka_data['id_karty'] . ':' . $userPesel); ?>"
                         alt="QR Code" class="qr-code mb-3">
                    <p><strong>Numer karty:</strong> <span class="card-number"><?php echo $mka_data['id_karty']; ?></span></p>
                    <p><strong>Ważna do:</strong> <?php echo $mka_data['formatted_expiry']; ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($rpk_data) && $rpk_data): ?>
    <!-- QR Modal RPK -->
    <div class="modal fade" id="qrRpkModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kontrola biletu - RPK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="<?php echo $cardController->generateQRCode('RPK:' . $rpk_data['id_karty'] . ':' . $userPesel); ?>"
                         alt="QR Code" class="qr-code mb-3">
                    <p><strong>Numer karty:</strong> <span class="card-number"><?php echo $rpk_data['id_karty']; ?></span></p>
                    <p><strong>Ważna do:</strong> <?php echo $rpk_data['formatted_expiry']; ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>