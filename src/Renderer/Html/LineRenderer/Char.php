<?php

declare(strict_types=1);

namespace Jfcherng\Diff\Renderer\Html\LineRenderer;

use Jfcherng\Diff\Renderer\RendererConstant;
use Jfcherng\Diff\SequenceMatcher;
use Jfcherng\Diff\Utility\ReverseIterator;
use Jfcherng\Utility\MbString;

final class Char extends AbstractLineRenderer
{
    /**
     * {@inheritdoc}
     */
    public function render(MbString $mbFrom, MbString $mbTo): LineRendererInterface
    {
        $opcodes = $this->getChangedExtentSegments($mbFrom->toArray(), $mbTo->toArray());

        // reversely iterate opcodes
        foreach (ReverseIterator::fromArray($opcodes) as [$tag, $i1, $i2, $j1, $j2]) {
            switch ($tag) {
                case SequenceMatcher::OP_DEL:
                    $mbFrom->str_enclose_i(RendererConstant::HTML_CLOSURES, $i1, $i2 - $i1);
                    break;
                case SequenceMatcher::OP_INS:
                    $mbTo->str_enclose_i(RendererConstant::HTML_CLOSURES, $j1, $j2 - $j1);
                    break;
                case SequenceMatcher::OP_REP:
                    $mbFrom->str_enclose_i(RendererConstant::HTML_CLOSURES, $i1, $i2 - $i1);
                    $mbTo->str_enclose_i(RendererConstant::HTML_CLOSURES, $j1, $j2 - $j1);
                    break;
                default:
                    continue 2;
            }
        }

        return $this;
    }
}
