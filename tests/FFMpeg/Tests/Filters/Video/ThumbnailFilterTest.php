<?php

namespace FFMpeg\Tests\Filters\Video;

use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\Filters\Video\ThumbnailFilter;
use FFMpeg\Tests\TestCase;

class ThumbnailFilterTest extends TestCase
{
    protected $priority = 123;
    protected $params = [
        'thumbnail_times' => 5,
    ];


    public function testParams() {
        $filter = new ThumbnailFilter($this->params, $this->priority);
        $this->assertEquals($this->priority, $filter->getPriority());

    }
    public function testApply($value)
    {
        $stream = new Stream([
            'height' => 240,
            'width' => 320,
            'codec_type' => 'video'
        ]);

        $streams = new StreamCollection($stream);

        $video = $this->getVideoMock();
        $video->expects($this->once())
            ->method('getStreams')
            ->will($this->returnValue($streams));
        $format = $this->getMock('FFMpeg\Format\VideoInterface');

        $filter = new ThumbnailFilter($this->params, $this->priority);
        $time = '11.00.00';
        $index = 1;

        $this->assertEquals(
            ['-ss', $time, '-f', 'image2', '-vframes', '1', 'thumb'.$index.'.png', ],
            $filter->apply($video, $format)
        );

    }

}
