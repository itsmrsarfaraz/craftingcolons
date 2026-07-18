<?php

namespace App\Traits;

trait HasSeoMeta
{
    public function seoTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function seoDescription(): string
    {
        return $this->meta_description ?: str($this->excerpt ?? strip_tags($this->body))->limit(160);
    }
}