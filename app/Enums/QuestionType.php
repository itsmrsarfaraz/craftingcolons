<?php

namespace App\Enums;

enum QuestionType: string
{
    case Mcq = 'mcq';
    case TrueFalse = 'true_false';
    case MultipleSelect = 'multiple_select';
    case ShortAnswer = 'short_answer';
    case LongAnswer = 'long_answer';
    case FileUpload = 'file_upload';
    case Coding = 'coding';

    public function label(): string
    {
        return match ($this) {
            self::Mcq => 'Multiple Choice',
            self::TrueFalse => 'True / False',
            self::MultipleSelect => 'Multiple Select',
            self::ShortAnswer => 'Short Answer',
            self::LongAnswer => 'Long Answer',
            self::FileUpload => 'File Upload',
            self::Coding => 'Coding',
        };
    }

    /**
     * Whether this question type uses question_options rows at all.
     */
    public function usesOptions(): bool
    {
        return in_array($this, [self::Mcq, self::TrueFalse, self::MultipleSelect]);
    }

    /**
     * Whether this question type can be auto-graded without human review.
     */
    public function isAutoGradable(): bool
    {
        return in_array($this, [self::Mcq, self::TrueFalse, self::MultipleSelect]);
    }
}