<?php
/**
 * Round-robin schedule generation implementation
 *
 * @author Michael P. Nitowski <mpnitowski@gmail.com>
 *
 * MIT License
 *
 * Copyright (c) 2021 Michael P. Nitowski
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

const MARKDOWN_EOL = PHP_EOL;

/**
 * Format a round-robin schedule as Markdown
 *
 * @param Schedule $schedule The schedule to use
 *
 * @param string|null $name The name of the schedule (optional)
 *
 * @param string|null $description The description of the schedule (optional)
 *
 * @return string The formatted schedule in Markdown
 */
function schedule_to_markdown(Schedule $schedule, string $name = null, string $description = null): string
{
    $s = "";

    if ($name) {
        $s .= '# ' . $name . MARKDOWN_EOL . MARKDOWN_EOL;
    }

    if($description) {
        $s .= wordwrap($description . MARKDOWN_EOL . MARKDOWN_EOL, 75, MARKDOWN_EOL);
    }

    $s .= '## Full Schedule' . MARKDOWN_EOL . MARKDOWN_EOL;

    foreach($schedule as $round => $matchups) {
        $s .= '### Round ' . $round  . MARKDOWN_EOL;
        foreach($matchups as $matchup) {
            $s .= '  * '
                . ($matchup[0] ?? '_BYE_') . ' vs. ' . ($matchup[1] ?? '_BYE_')
                . MARKDOWN_EOL;
        }
        $s .= MARKDOWN_EOL;
    }

    $s .= '## Individual Schedules' . MARKDOWN_EOL . MARKDOWN_EOL;

    foreach($schedule->teams() as $team) {
        $s .= '### ' . $team . MARKDOWN_EOL;
        foreach($schedule($team) as $round => $contest) {
            $s .= '  ' . $round . '. '
                . ($contest['home'] ? '' : '@') . ($contest['team'] ?? '_BYE_')
                . MARKDOWN_EOL;
        }
        $s .= MARKDOWN_EOL;
    }

    return $s;
}
?>
