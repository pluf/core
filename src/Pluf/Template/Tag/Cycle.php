<?php

/**
 * Template tag <code>cycle</code>.
 *
 * Cycle among the given strings or variables each time this tag is
 * encountered.
 *
 * Within a loop, cycles among the given strings each time through the loop:
 *
 * <code>
 * {foreach $some_list as $obj}
 * <tr class="{cycle 'row1', 'row2'}">
 * ...
 * </tr>
 * {/foreach}
 * </code>
 *
 * You can use variables, too. For example, if you have two
 * template variables, $rowvalue1 and $rowvalue2, you can
 * cycle between their values like this:
 *
 * <code>
 * {foreach $some_list as $obj}
 * <tr class="{cycle $rowvalue1, $rowvalue2}">
 * ...
 * </tr>
 * {/foreach}
 * </code>
 *
 * You can mix variables and strings:
 *
 * <code>
 * {foreach $some_list as $obj}
 * <tr class="{cycle 'row1', rowvalue2, 'row3'}">
 * ...
 * </tr>
 * {/foreach}
 * </code>
 *
 * In some cases you might want to refer to the next value of a cycle
 * from outside of a loop. To do this, just group the arguments into
 * an array and give the {cycle} tag name last, like this:
 *
 * <code>
 * {cycle array('row1', 'row2'), 'rowcolors'}
 * </code>
 *
 * From then on, you can insert the current value of the cycle
 * wherever you'd like in your template:
 *
 * <code>
 * <tr class="{cycle $rowcolors}">...</tr>
 * <tr class="{cycle $rowcolors}">...</tr>
 *
 * Based on concepts from the Django cycle template tag.
 */
class Pluf_Template_Tag_Cycle extends Pluf_Template_Tag
{

    /**
     *
     * @see Pluf_Template_Tag::start()
     * @throws InvalidArgumentException If no argument is provided.
     */
    public function start ()
    {
        $nargs = func_num_args();
        if (1 > $nargs) {
            throw new InvalidArgumentException(
                    '`cycle` tag requires at least one argument');
        }
        
        $result = '';
        list ($key, $index) = $this->_computeIndex(func_get_args());
        
        switch ($nargs) {
            // (array or mixed) argument
            case 1:
                $arg = func_get_arg(0);
                if (is_array($arg)) {
                    $result = $arg[$index % count($arg)];
                } else {
                    $result = $arg;
                }
                break;
            
            // (array) arguments, (string) assign
            case 2:
                $args = func_get_args();
                if (is_array($args[0])) {
                    $last = array_pop($args);
                    if (is_string($last) && '' === $this->context->get($last)) {
                        $value = Pluf_Utils::flattenArray($args[0]);
                        $this->context->set($last, $value);
                        
                        list ($assign_key, $assign_index) = $this->_computeIndex(
                                array(
                                        $value
                                ));
                        $result = $value[0];
                    }
                    break;
                }
            
            // considers all the arguments as a value to use in the cycle
            default:
                $args = Pluf_Utils::flattenArray(func_get_args());
                $result = $args[$index % count($args)];
                break;
        }
        
        echo Pluf_Template::markSafe((string) $result);
    }

    /**
     * Compute an index for the given array.
     *
     * @param
     *            array
     * @return array A array of two elements: key and index.
     */
    protected function _computeIndex ($args)
    {
        if (! isset($this->context->__cycle_stack)) {
            $this->context->__cycle_stack = array();
        }
        
        $key = serialize($args);
        $this->context->__cycle_stack[$key] = (array_key_exists($key, 
                $this->context->__cycle_stack)) ? 1 +
                 $this->context->__cycle_stack[$key] : 0;
        $index = $this->context->__cycle_stack[$key];
        
        return array(
                $key,
                $index
        );
    }
}
