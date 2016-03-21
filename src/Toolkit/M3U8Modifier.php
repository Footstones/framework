<?php

namespace Footstones\Framework\Toolkit;

class M3U8Modifier
{
    protected $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * 替换片段的域名
     * @return 
     */
    public function replaceSegmentDomain($domain)
    {
        $domain = rtrim($domain, '\/');

        $this->content = preg_replace_callback('/\#EXTINF\s*:(.+?),.*?(\S+)[\r\n]/is', function($matches) use ($domain) {

            $duration = $matches[1];

            if (stripos($matches[2], 'http') === 0) {
                $url = preg_replace('/(https?:\/\/.*?)\/(.*)/', "{$domain}/$2", $matches[2]);
            } else {
                $url = $domain . '/' . ltrim($matches[2], '\/');
            }

            return "#EXTINF:{$duration},\n$url\n";

        }, $this->content);

        return $this;
    }

    public function replaceKeyUrl($url)
    {
        $this->content = preg_replace_callback('/(#EXT-X-KEY.*?)URI\s*=\s*["\'](.*?)["\']/is', function($matches) use ($url) {
            return $matches[1] . "URI=\"{$url}\"";
        }, $this->content);

        return $this;
    }

    /**
     * 合并M3U8
     */
    public function merge($content)
    {
        $firstMaxDuration = $this->parseSegmentMaxDuration();

        $firstSegmengBodyStartPos = $this->getSegmentBodyStartPos();

        $firstPart1 = trim(substr($this->content, 0, $firstSegmengBodyStartPos));
        $firstPart2 = trim(substr($this->content, $firstSegmengBodyStartPos));

        $modifier = new self($content);
        $secondMaxDuration = $modifier->parseSegmentMaxDuration();
        $secondBody = $modifier->cutSegmentBody();

        $m3u8 = $firstPart1 . "\n" . $secondBody . "\n" . $firstPart2;


        var_dump($m3u8);exit();
    }

    /**
     * 切出片段的BODY
     * @return [type] [description]
     */
    public function cutSegmentBody()
    {
        $startPos = $this->getSegmentBodyStartPos();
        $endPos = $this->getSegmentBodyEndPos();

        if (empty($startPos) || empty($endPos)) {
            throw new \RuntimeException("切出片段的BODY出错，未找到BODY的起始、结束位置(start: {$startPos}, end:{$endPos})");
        }

        return $this->content = trim(substr($this->content, $startPos, $endPos-$startPos));
    }

    private function getSegmentBodyStartPos()
    {
        $starts = ['#EXT-X-KEY', '#EXTINF'];

        $startPos = false;
        foreach ($starts as $start) {
            $pos = stripos($this->content, $start);
            if ($pos !== false) {
                $startPos = $pos;
                break;
            }
        }

        return $startPos;
    }

    private function getSegmentBodyEndPos()
    {
        return stripos($this->content, '#EXT-X-ENDLIST');
    }

    /**
     * 重设片段的最大时长标签
     * 
     * @param  [type] $duration [description]
     * @return [type]           [description]
     */
    public function resetSegmentMaxDuration($duration)
    {
        $duration = ceil($duration);
        $this->content = preg_replace('/#EXT-X-TARGETDURATION.+?[\r\n]/is', "#EXT-X-TARGETDURATION:{$duration}\n", $this->content);
        return $this;
    }

    public function parseSegmentMaxDuration()
    {
        preg_match('/#EXT-X-TARGETDURATION\s*:(\S+)/', $this->content, $matches);
        if (empty($matches[1])) {
            throw new \RuntimeException('Parse EXT-X-TARGETDURATION error.');
        }

        return ceil($matches[1]);
    }

    public function getContent()
    {
        return $this->content;
    }

}