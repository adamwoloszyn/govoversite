<?php
    namespace App\Parsing;

    class TokenBase {
        public $value;
        public $lineNumber;

        function __construct() {
            $this->value = "";
        }

        public function Output()
        {
            return get_class($this) . " : " . $this->value . " : Line # " . $this->lineNumber;
        }

        public static function classFactory($type, $value)
        {
            //echo "Class Factory Input: " . $type . "[" . $value . "]\n";

            switch ($type) {
                case 'DATE':
                    return new DateToken( $value );

                case 'TIME':
                    return new TimeToken( $value );

                case 'LEFTBRACKET':
                    return new LeftBracketToken( );

                case 'RIGHTTBRACKET' :
                    return new RightBracketToken( );

                case 'IDENTIFIER' :
                case 'INTEGER' : 
                case 'CURRENCY' :
                case 'DECIMAL' :
                    return new WordToken( $value );

                case 'ASSIGNMENT' :
                    return new AssignmentToken();

                case 'OPERATOR' :
                    return new OperatorToken( $value );

                case 'WHITESPACE' :
                    return new WhiteSpaceToken();

                case 'QUOTE' :
                    return new QuoteToken();

                case 'PERIOD' :
                    return new PeriodToken();

                default:
                    return new UnknownToken($value);
            }
        }   // end of class method classFactory()
    }   // end of IgnoreWords class

    class Token extends TokenBase {
        function __construct($value) {
            $this->value = $value;
        }
    }   // end of Token class

    class EOFToken extends Token {
        function __construct() {
            parent::__construct("EOF");
        }
    }   // end of EOFToken class

    class WordToken extends Token {
        function __construct($value) {
            parent::__construct($value);
        }
    }

    class NumbericToken extends Token {
        function __construct($value) {
            parent::__construct($value);
        }
    }

    class DateToken extends Token {
        function __construct($value) {
            parent::__construct($value);
        }
    }

    class TimeToken extends Token {
        function __construct($value) {
            parent::__construct($value);
        }
    }
    
    class OperatorToken extends Token {
        function __construct($value) {
            parent::__construct($value);
        }
    }

    class UnknownToken extends Token {
        function __construct($value) {
            parent::__construct($value);
        }
    }    

    class PeriodToken extends TokenBase {
    }

    class LeftBracketToken extends TokenBase {
    }

    class RightBracketToken extends TokenBase {
    }

    class WhiteSpaceToken extends TokenBase {
    }

    class AssignmentToken extends TokenBase {
    }

    class QuoteToken extends TokenBase {
    }
?>