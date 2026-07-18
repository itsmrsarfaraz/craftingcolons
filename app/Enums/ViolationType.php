<?php

namespace App\Enums;

enum ViolationType: string
{
    case TabSwitch = 'tab_switch';
    case WindowBlur = 'window_blur';
    case VisibilityHidden = 'visibility_hidden';
    case FullscreenExit = 'fullscreen_exit';
    case DevtoolsDetected = 'devtools_detected';
    case CopyAttempt = 'copy_attempt';
    case PasteAttempt = 'paste_attempt';
    case MultipleWindows = 'multiple_windows';

    public function label(): string
    {
        return match ($this) {
            self::TabSwitch => 'Switched Tab',
            self::WindowBlur => 'Window Lost Focus',
            self::VisibilityHidden => 'Page Hidden / Minimized',
            self::FullscreenExit => 'Exited Fullscreen',
            self::DevtoolsDetected => 'Developer Tools Opened',
            self::CopyAttempt => 'Copied Content',
            self::PasteAttempt => 'Pasted Content',
            self::MultipleWindows => 'Multiple Windows/Tabs Open',
        };
    }

    /**
     * Some violations are severe enough to disqualify instantly,
     * regardless of the configured threshold (per spec: "Fail instantly if...").
     */
    public function isInstantFail(): bool
    {
        return in_array($this, [
            self::DevtoolsDetected,
            self::MultipleWindows,
        ]);
    }
}