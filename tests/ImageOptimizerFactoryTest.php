<?php

namespace Spatie\ImageOptimizer\Test;

use Spatie\ImageOptimizer\Optimizers\Optipng;
use Spatie\ImageOptimizer\Optimizers\Gifsicle;
use Spatie\ImageOptimizer\Optimizers\Pngquant;
use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use Spatie\ImageOptimizer\ImageOptimizerFactory;

class ImageOptimizerFactoryTest extends TestCase
{
    /** @var \Spatie\ImageOptimizer\ImageOptimizer */
    protected $imageOptimizer;

    public function setUp()
    {
        parent::setUp();

        $this->imageOptimizer = ImageOptimizerFactory::create()
            ->useLogger($this->log);
    }

    /** @test */
    public function it_can_optimize_a_jpg()
    {
        $tempFilePath = $this->getTempFilePath('test.jpg');

        $this->imageOptimizer->optimize($tempFilePath);

        $this->assertDecreasedFileSize($tempFilePath, $this->getTestFilePath('test.jpg'));

        $this->assertOptimizersUsed(Jpegoptim::class);
    }

    /** @test */
    public function it_can_optimize_a_png()
    {
        $tempFilePath = $this->getTempFilePath('test.png');

        $this->imageOptimizer->optimize($tempFilePath);

        $this->assertDecreasedFileSize($tempFilePath, $this->getTestFilePath('test.png'));

        $this->assertOptimizersUsed([
            Optipng::class,
            Pngquant::class,
        ]);
    }

    /** @test */
    public function it_can_optimize_a_gif()
    {
        $tempFilePath = $this->getTempFilePath('test.gif');

        $this->imageOptimizer->optimize($tempFilePath);

        $this->assertDecreasedFileSize($tempFilePath, $this->getTestFilePath('test.gif'));

        $this->assertOptimizersUsed(Gifsicle::class);
    }

    /** @test */
    public function it_will_not_not_touch_a_non_image_file()
    {
        $tempFilePath = $this->getTempFilePath('test.txt');

        $originalContent = file_get_contents($tempFilePath);

        $this->imageOptimizer->optimize($tempFilePath);

        $optimizedContent = file_get_contents($tempFilePath);

        $this->assertEquals($optimizedContent, $originalContent);
    }
}