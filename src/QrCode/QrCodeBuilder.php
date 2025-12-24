<?php

namespace Radlinger\Mealplan\QrCode;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Exception\ValidationException;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;

/**
 * Class QrCodeBuilder
 *
 * Wrapper around the endroid/qr-code library.
 * This class centralizes QR code creation logic
 * and keeps it out of controllers and views.
 */
class QrCodeBuilder
{
    /**
     * Generate a QR code.
     *
     * @param string $data Data encoded inside the QR code (e.g. URL)
     * @param string $lable Optional label displayed below the QR code
     *
     * @return \Endroid\QrCode\Builder\BuilderInterface
     */
    public static function generate(string $data, string $lable, int $size = 300, int $margin = 10): ?ResultInterface
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $size,
            margin: $margin,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            labelText: $lable,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );

        try {
            return $builder->build();
        } catch (ValidationException) {
            return null;
        }
    }
}
