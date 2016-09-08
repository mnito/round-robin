<?php
/**
 * Round-robin schedule generation library
 * 
 * @author Michael P. Nitowski <mpnitowski@gmail.com>
 * 
 * MIT License
 *
 * Copyright (c) 2016 Michael P. Nitowski
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

/**
 * Rotate array items according to the round-robin algorithm
 * 
 * @param array $items
 * @return void
 */
function rotate(array &$items)
{
    $itemCount = count($items);
    if($itemCount < 3) {
        return;
    }
    $lastIndex = $itemCount - 1;
    /**
     * Though not technically part of the round-robin algorithm, odd-even 
     * factor differentiation included to have intuitive behavior for arrays 
     * with an odd number of elements
     */
    $factor = (int) $itemCount % 2 === 0 ? $itemCount / 2 : ($itemCount / 2) + 1;
    $topRightIndex = $factor - 1;
    $topRightItem = $items[$topRightIndex];
    $bottomLeftIndex = $factor;
    $bottomLeftItem = $items[$bottomLeftIndex];
    for($i = $topRightIndex; $i > 0; $i -= 1) {
        $items[$i] = $items[$i - 1];
    }
    for($i = $bottomLeftIndex; $i < $lastIndex; $i += 1) {
        $items[$i] = $items[$i + 1];
    }
    $items[1] = $bottomLeftItem;
    $items[$lastIndex] = $topRightItem;
}
