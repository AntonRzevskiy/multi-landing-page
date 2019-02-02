<?php

/**
 * The file that defines the class which process mask string.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes/ext
 */

/**
 * Define the functions works with mask field.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Mask {


	/**
	 * String for processing.
	 *
	 * @since      1.0.0
	 *
	 * @var        string         $input_string  .
	 */
	 public $input_string = '';

	/**
	 * Words to be excluded from the mask.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $stop_words    .
	 */
	public $stop_words = array(

		'a', 'about', 'all', 'am', 'an', 'and', 'any', 'are', 'as', 'at',
		'be', 'been', 'but', 'by',
		'can', 'could',
		'do',
		'for', 'from',
		'has', 'have',
		'i', 'if', 'in', 'is', 'it',
		'me', 'my',
		'no', 'not',
		'of', 'on', 'one', 'or',
		'so',
		'that', 'the', 'them', 'there', 'they', 'this', 'to',
		'was', 'we', 'what', 'which', 'will', 'with', 'would',
		'you',

		'а',
		'будем', 'будет', 'будете', 'будешь', 'буду', 'будут', 'будучи', 'будь', 'будьте', 'бы', 'был', 'была', 'были', 'было', 'быть',
		'в', 'вам', 'вами', 'вас', 'весь', 'во', 'вот', 'все', 'всё', 'всего', 'всей', 'всем', 'всём', 'всеми', 'всему', 'всех', 'всею', 'всея', 'всю', 'вся', 'вы',
		'да', 'для', 'до',
		'его', 'едим', 'едят', 'ее', 'её', 'ей', 'ел', 'ела', 'ем', 'ему', 'емъ', 'если', 'ест', 'есть', 'ешь', 'еще', 'ещё', 'ею',
		'же',
		'за',
		'и', 'из', 'или', 'им', 'ими', 'имъ', 'их',
		'к', 'как', 'кем', 'ко', 'когда', 'кого', 'ком', 'кому', 'комья', 'которая', 'которого', 'которое', 'которой', 'котором', 'которому', 'которою', 'которую', 'которые', 'который', 'которым', 'которыми', 'которых', 'кто',
		'меня', 'мне', 'мной', 'мною', 'мог', 'моги', 'могите', 'могла', 'могли', 'могло', 'могу', 'могут', 'мое', 'моё', 'моего', 'моей', 'моем', 'моём', 'моему', 'моею', 'можем', 'может', 'можете', 'можешь', 'мои', 'мой', 'моим', 'моими', 'моих', 'мочь', 'мою', 'моя', 'мы',
		'на', 'нам', 'нами', 'нас', 'наш', 'наша', 'наше', 'нашего', 'нашей', 'нашем', 'нашему', 'нашею', 'наши', 'нашим', 'нашими', 'наших', 'нашу', 'не', 'него', 'нее', 'неё', 'ней', 'нем', 'нём', 'нему', 'нет', 'нею', 'ним', 'ними', 'них', 'но',
		'о', 'об', 'один', 'одна', 'одни', 'одним', 'одними', 'одних', 'одно', 'одного', 'одной', 'одном', 'одному', 'одною', 'одну', 'он', 'она', 'оне', 'они', 'оно', 'от',
		'по', 'при',
		'с', 'сам', 'сама', 'сами', 'самим', 'самими', 'самих', 'само', 'самого', 'самом', 'самому', 'саму', 'свое', 'своё', 'своего', 'своей', 'своем', 'своём', 'своему', 'своею', 'свои', 'свой', 'своим', 'своими', 'своих', 'свою', 'своя', 'себе', 'себя', 'собой', 'собою',
		'та', 'так', 'такая', 'такие', 'таким', 'такими', 'таких', 'такого', 'такое', 'такой', 'таком', 'такому', 'такою', 'такую', 'те', 'тебе', 'тебя', 'тем', 'теми', 'тех', 'то', 'тобой', 'тобою', 'того', 'той', 'только', 'том', 'томах', 'тому', 'тот', 'тою', 'ту', 'ты',
		'у', 'уже',
		'чего', 'чем', 'чём', 'чему', 'что', 'чтобы',
		'эта', 'эти', 'этим', 'этими', 'этих', 'это', 'этого', 'этой', 'этом', 'этому', 'этот', 'этою', 'эту',
		'я',

	);

	/**
	 * The resulting data.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $result        .
	 */
	public $result = array(

		'input'        => '', // original string
		'output'       => '', // string after processing

		'input_count'  => 0, // words count in source string

		'strict'       => false, // true if the string is wrapped in quotes

		'words'        => array( // array of formatted words

			'fixed_order'    => array(), // []
			'group_words'    => array(), // (|)
			'fixed_form'     => array(), // !
			'required_words' => array(), // +
			'other_words'    => array(),

		),

		'words_count'  => 0, // words count in formatted data with the words in the group

		'pattern_length' => 0, // words count in formatted data

		'stop_words'   => array(), // stop-words

	);

	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct( $string ) {

		$this->input_string = $string;

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function is_strict( $string ) {

		return (bool) preg_match( '/^".+"$/', $string );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function trim_space( $string ) {

		return preg_replace( '/\s\s+/', ' ', trim( $string ) );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function trim_quotes( $string ) {

		return preg_replace( '/"/', '', $string );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function trim_special( $string ) {

		$patterns = array(

			'/(!)\s+/'                 => '\\1', // space after !
			'/(\+)\s+/'                => '\\1', // space after +

			'/(\[)\s+/'                => '\\1', // space after [
			'/(\|)\s+/'                => '\\1', // space after |
			'/(\()\s+/'                => '\\1', // space after (

			'/\s+(\])/'                => '\\1', // space before ]
			'/\s+(\|)/'                => '\\1', // space before |
			'/\s+(\))/'                => '\\1', // space before )

		);

		$string = preg_replace( array_keys( $patterns ), $patterns, $string );

		return $string;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function flush_result() {

		$this->result = array(

			'input'        => '',
			'output'       => '',

			'input_count'  => 0,

			'strict'       => false,

			'words'        => array(

				'fixed_order'    => array(),
				'group_words'    => array(),
				'fixed_form'     => array(),
				'required_words' => array(),
				'other_words'    => array(),

			),

			'words_count'  => 0,

			'pattern_length' => 0,

			'stop_words'   => array(),

		);

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function parse() {

		$this->flush_result();

		$str = $this->result[ 'input' ] = $this->input_string;

		$str = $this->trim_space( $str );

		$str = $this->trim_special( $str );

		$this->result[ 'strict' ] = $this->is_strict( $str );

		$str = $this->trim_quotes( $str );

		$str = $this->trim_space( $str );

		$this->result[ 'input_count' ] = count( explode( ' ', $str ) );


		$str = $this->extract_fixed_order( $str );

		$str = $this->extract_group_words( $str );

		$str = $this->extract_fixed_form( $str );

		$str = $this->extract_required_words( $str );


		if ( false === $this->result[ 'strict' ] ) {

			$str = $this->extract_stop_words( $str );

		}


		$this->result[ 'words' ][ 'other_words' ] = array_keys( array_count_values( explode( ' ', $str ) ) );


		$this->count_words();

		$this->result[ 'output' ] = $str;

		return $this;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function extract_fixed_order( $string ) {

		$string = preg_replace_callback( '/\[(.+?)\]/u', array( $this, '_extract_fixed_order' ), $string );

		return $this->trim_space( $string );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function _extract_fixed_order( $matches ) {

		$this->result[ 'words' ][ 'fixed_order' ][] = $matches[ 1 ];

		return '';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function extract_group_words( $string ) {

		$string = preg_replace_callback( '/(?<=\W)\(([\w\|]+?)\)(?!\w)/u', array( $this, '_extract_group_words' ), $string );

		return $this->trim_space( $string );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function _extract_group_words( $matches ) {

		$this->result[ 'words' ][ 'group_words' ][] = $matches[ 1 ];

		return '';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function extract_fixed_form( $string ) {

		$string = preg_replace_callback( '/!([\w-]+)/u', array( $this, '_extract_fixed_form' ), $string );

		return $this->trim_space( $string );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function _extract_fixed_form( $matches ) {

		$this->result[ 'words' ][ 'fixed_form' ][] = $matches[ 1 ];

		return '';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function extract_required_words( $string ) {

		$string = preg_replace_callback( '/\+([\w-]+)/u', array( $this, '_extract_required_words' ), $string );

		return $this->trim_space( $string );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function _extract_required_words( $matches ) {

		$this->result[ 'words' ][ 'required_words' ][] = $matches[ 1 ];

		return '';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function extract_stop_words( $string ) {

		$string = preg_replace_callback( '/\b([\w-]+)\b\s?/u', array( $this, '_extract_stop_words' ), $string );

		return $this->trim_space( $string );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function _extract_stop_words( $matches ) {

		if ( in_array( $matches[ 1 ], $this->stop_words ) ) {

			$this->result[ 'stop_words' ][] = $matches[ 1 ];

			return '';
		}

		return $matches[ 0 ];
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function count_words() {

		foreach( $this->result[ 'words' ] as $group ) {

			$this->result[ 'pattern_length' ] += count( $group );

			foreach( $group as $string ) {

				$this->result[ 'words_count' ] += count( explode( ' ', $string ) );

			}

		}

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function create_regexp() {

		$words = array();

		foreach( $this->result[ 'words' ] as $group_name => $group ) {

			foreach( $group as $word ) {

				$words[] = $this->add_word_regexp( $word, $group_name );

			}

		}

		$length = $this->result[ 'pattern_length' ];

		$matches = implode( '(\s.*|$)|', $words );

		if ( $this->result[ 'strict' ] ) {

			// return "^({$matches}(\s.*|$)){{$length}}$";
		}

		return "({$matches}(\s.*|$)){{$length}}";
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function add_word_regexp( $word, $group_name = '' ) {

		if ( 'group_words' === $group_name ) {

			$word = '(' . $word . ')';

		}

		return $word;
	}


}
