<?php

namespace SslCorp\Interfaces;

/**
 * @property string $ref
 * @property string $order_status
 * @property string $order_amount
 * @property string $external_order_number
 * @property string $certificate_url
 * @property string $receipt_url
 * @property string $smart_seal_url
 * @property string $validation_url
 * @property null $validations
 * @property null $certificates
 * @property Registrant|null $registrant
 * @property CertificateContents $certificate_contents
 */
interface CreateCertificateRes
{
}
