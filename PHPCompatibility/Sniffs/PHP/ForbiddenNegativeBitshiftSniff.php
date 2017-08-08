<?php
/**
 * \PHPCompatibility\Sniffs\PHP\ForbiddenNegativeBitshift.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\Sniffs\PHP\ForbiddenNegativeBitshift.
 *
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */
class ForbiddenNegativeBitshiftSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_SR);

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.0') === false) {
            return;
        }

        $nextNumber = $phpcsFile->findNext(T_LNUMBER, $stackPtr + 1, null, false, null, true);
        if ($nextNumber === false || ($stackPtr + 1) === $nextNumber) {
            return;
        }
        
        $MinusSign = $phpcsFile->findNext(T_MINUS, $stackPtr + 1, $nextNumber, false, null, true);
        if ($MinusSign === false) {
            return;
        }

        $nextVariable = $phpcsFile->findNext(array(T_VARIABLE, T_CONST, T_STRING, T_DNUMBER, T_CONSTANT_ENCAPSED_STRING, T_STRING_VARNAME, T_NUM_STRING, T_ENCAPSED_AND_WHITESPACE), $stackPtr + 1, $MinusSign, false, null, true);
        if ($nextVariable !== false) {
            return;
        }
        
        $phpcsFile->addError(
            'Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0',
            $MinusSign,
            'Found'
        );

    }//end process()

}//end class
