<?php
class parser_cleancss extends driver {
	public function css($code)
		{
		return CleanCSS::convertString($code);
		}
}

/*
© 2010 Massimiliano Torromeo

This program is distributed under the terms of the BSD License.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:

1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

class CleanCSS_ParserException extends Exception {}

class CleanCSS {
	protected $source;
	const version = '1.4';

	public function __construct($file, $source=null) {
		if (is_null($source))
			$this->source = file_get_contents($file);
		else
			$this->source = $source;
	}

	protected function flattenSelectors($selectorTree) {
		$selectors = array();
		$base = array_shift($selectorTree);
		$tails = null;
		if (count($selectorTree)>0)
			$tails = $this->flattenSelectors($selectorTree);
		foreach($base as $i => $sel) {
			if (!is_null($tails)) {
				foreach($tails as $tail) {
					if ($tail[0] == '&')
						$tail = substr($tail,1);
					else
						$tail = " $tail";
					$selectors[] = $sel.$tail;
				}
			} else {
				$selectors[] = $sel;
			}
		}
		return $selectors;
	}

	public function toCss() {
		$level = 0;
		$indenter = 0;
		$selectorsChanged = False;
		$rules = array();
		$cur_rule_tree = array();
		$rule_prefixes = array();

		foreach (explode("\n", $this->source) as $lineno => $line) {
			if (trim($line) == '') continue;

			preg_match('/^\s*/', $line, $matches);
			$indentation = $matches[0];
			if ($indenter == 0 && strlen($indentation)>0)
				$indenter = strlen($indentation);

			if ($indenter>0 && strlen($indentation) % $indenter != 0)
				throw new CleanCSS_ParserException("Indentation error. Line: $lineno.");

			$newlevel = $indenter > 0 ? strlen($indentation) / $indenter : 0;
			$line = trim($line);

			if ($newlevel-$level>1)
				throw new CleanCSS_ParserException("Indentation error. Line: $lineno.");

			# Pop to new level
			while (count($cur_rule_tree)+count($rule_prefixes)>$newlevel && count($rule_prefixes)>0)
				array_pop($rule_prefixes);
			while (count($cur_rule_tree)>$newlevel)
				array_pop($cur_rule_tree);
			$level = $newlevel;

			if (preg_match('/^(.+)\s*:$/', $line, $matches)) {
				$selectors = explode(',', $matches[1]);
				foreach ($selectors as $i => $sel)
					$selectors[$i] = trim($sel);
				$cur_rule_tree[] = $selectors;
				$selectorsChanged = True;
				continue;
			}

			if (preg_match('/^([^:>\s]+)->$/', $line, $matches)) {
				$rule_prefixes[] = $matches[1];
				continue;
			}

			if (preg_match('/^([^\s]+)\s*:\s*(.+)$/', $line, $matches)) {
				if (count($cur_rule_tree) == 0)
					throw new CleanCSS_ParserException("Selector expected, found definition. Line: $lineno.");
				if ($selectorsChanged) {
					$selectors = implode(",\n", $this->flattenSelectors($cur_rule_tree));
					$rules[] = array($selectors, array());
					$selectorsChanged = False;
				}
				if (count($rule_prefixes)>0)
					$prefixes = implode('-', $rule_prefixes) . '-';
				else
					$prefixes = '';
				$rules[count($rules)-1][1][] = $prefixes . $matches[1] . ': ' . $matches[2] . ';';
				continue;
			}

			throw new CleanCSS_ParserException("Unexpected item. Line: $lineno.");
		}

		$result = array();
		foreach ($rules as $rule)
			$result[] = $rule[0] . " {\n\t" . implode("\n\t", $rule[1]) . "\n}\n";
		return implode('', $result);
	}

	public static function convert($file) {
		$ccss = new CleanCSS($file);
		return $ccss->toCss();
	}

	public static function convertString($source) {
		$ccss = new CleanCSS(null, $source);
		return $ccss->toCss();
	}

	public static function output($file) {
		header('Content-Type: text/css');
		echo CleanCSS::convert($file);
	}

}
