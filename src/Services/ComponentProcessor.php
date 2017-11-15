<?php
declare(strict_types=1);

namespace App\Services;


/**
 * Class ComponentProcessor
 */
class ComponentProcessor
{

    /**
     * @var ImageGenerator
     */
    private $imageGenerator;

    /**
     * @var TextGenerator
     */
    private $textGenerator;

    /**
     * ComponentProcessor constructor.
     *
     * @param ImageGenerator $imageGenerator
     * @param TextGenerator  $textGenerator
     */
    public function __construct(ImageGenerator $imageGenerator, TextGenerator $textGenerator)
    {
        $this->imageGenerator = $imageGenerator;
        $this->textGenerator = $textGenerator;
    }

}
