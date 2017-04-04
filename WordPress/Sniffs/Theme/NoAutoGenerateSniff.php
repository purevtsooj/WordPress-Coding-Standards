<?php
/**
 * WordPress Coding Standard.
 *
 * @package WPCS\WordPressCodingStandards
 * @link    https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

/**
 * Check for auto generated themes.
 *
 * @package WPCS\WordPressCodingStandards
 *
 * @since   0.xx.0
 */
class WordPress_Sniffs_Theme_NoAutoGenerateSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var array
	 */
	public $supportedTokenizers = array(
		'PHP',
		'CSS',
	);

	/**
	 * A list of strings found in the generated themes.
	 *
	 * @var array
	 */
	protected $generated_themes = array(
		'artisteer', // Artisteer.
		'themler', // Themler.
		'lubith', // Lubith.
		'templatetoaster', // TemplateToaster.
		'wpthemegenerator', // WP Theme Generator.
		'pinegrow', // Pinegrow.
	);

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		$tokens   = PHP_CodeSniffer_Tokens::$textStringTokens;
		$tokens[] = T_STRING; // Functions named after or prefixed with the generator name.
		$tokens[] = T_COMMENT;
		$tokens[] = T_DOC_COMMENT_STRING;
		return $tokens;
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token
	 *                                        in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();
		$token  = $tokens[ $stackPtr ];
		$content = trim( strtolower( $token['content'] ) );

		// No need to check an empty string.
		if ( '' === $content ) {
			return;
		}

		foreach ( $this->generated_themes as $generated_theme ) {
			/*
			 * The check can have false positives like artisteers but chances of that happening
			 * in a valid theme is low.
			 */
			if ( false === strpos( $content, $generated_theme ) ) {
				continue;
			}

			$phpcsFile->addError(
				'Auto generated themes are not allowed in the theme directory. Found: %s',
				$stackPtr,
				'AutoGeneratedFound',
				array( $generated_theme )
			);
		}
	}

}
