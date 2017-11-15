<?php
declare(strict_types=1);

namespace App\Services;

use Intervention\Image\ImageManager;


/**
 * Class ImageGenerator
 */
class ImageGenerator
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * ImageGenerator constructor.
     *
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

}
