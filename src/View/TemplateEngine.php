<?php

namespace Radlinger\Mealplan\View;

class TemplateEngine
{
    /**
     * Renders an HTML template and injects dynamic data.
     *
     * @param string $templatePath Path to the template file (HTML)
     * @param array $data Associative array containing all variables
     *                             and arrays that should be available
     *                             inside the template
     *
     * @return string Fully rendered HTML as a string
     */
    public static function render(string $templatePath, array $data): string
    {
        /**
         * ------------------------------------------------------------
         * STEP 1: LOAD TEMPLATE FILE
         * ------------------------------------------------------------
         * The template is loaded as a plain string.
         * fread() is used instead of file_get_contents()
         * to explicitly demonstrate file handling.
         */
        $handle = fopen($templatePath, 'r');
        $template = fread($handle, filesize($templatePath));
        fclose($handle);

        /**
         * ------------------------------------------------------------
         * STEP 2: HANDLE MAIN LOOP STRUCTURES
         * ------------------------------------------------------------
         *
         * Supported loop syntax inside templates:
         *
         * {% for plan in plans %}
         *   {{plan_name}}
         * {% endfor %}
         *
         * - "plans" must exist as an array in $data
         * - each element inside "plans" is treated as $item
         */
        if (preg_match_all(
            '/\{% for (\w+) in (\w+) %}(.*?)\{% endfor %}/s',
            $template,
            $matches,
            PREG_SET_ORDER
        )) {

            /**
             * Each match represents ONE complete loop block.
             */
            foreach ($matches as $match) {

                /**
                 * Breakdown of regex match:
                 *
                 * $fullMatch  → entire loop including tags
                 * $itemVar    → variable name used in template (e.g. "plan")
                 * $arrayVar   → array name from $data (e.g. "plans")
                 * $loopContent→ inner HTML of the loop
                 */
                [$fullMatch, $itemVar, $arrayVar, $loopContent] = $match;

                /**
                 * This string will collect the rendered result
                 * of all loop iterations.
                 */
                $replacement = '';

                /**
                 * IMPORTANT RULE:
                 * The array used in the loop MUST exist
                 * at the ROOT level of the $data array.
                 */
                if (isset($data[$arrayVar]) && is_array($data[$arrayVar])) {

                    /**
                     * ------------------------------------------------
                     * STEP 2.1: ITERATE OVER ARRAY ITEMS
                     * ------------------------------------------------
                     * Each element represents one logical entity
                     * (e.g. one meal plan).
                     */
                    foreach ($data[$arrayVar] as $item) {

                        /**
                         * Copy loop content for this iteration.
                         * This ensures that replacements do not
                         * affect the next iteration.
                         */
                        $renderedItemBlock = $loopContent;

                        /**
                         * Convert object properties into an array
                         * so we can iterate over key-value pairs.
                         */
                        $vars = is_object($item)
                            ? get_object_vars($item)
                            : (array)$item;

                        /**
                         * --------------------------------------------
                         * STEP 2.2: REPLACE SIMPLE VARIABLES
                         * --------------------------------------------
                         * Example:
                         * {{plan_name}} → value from the current item
                         */
                        foreach ($vars as $key => $value) {
                            if (!is_array($value) && !is_object($value)) {
                                $renderedItemBlock =
                                    str_replace('{{' . $key . '}}', $value, $renderedItemBlock);
                            }
                        }

                        /**
                         * --------------------------------------------
                         * STEP 2.3: HANDLE NESTED SUB-LOOPS
                         * --------------------------------------------
                         *
                         * Supported syntax:
                         *
                         * {% subFor meal in plan_meals %}
                         *   {{name}} {{price}}
                         * {% endSubFor %}
                         *
                         * Sub-loops DO NOT access $data directly!
                         * They only access properties of the current
                         * parent item (e.g. a single meal plan).
                         */
                        if (preg_match_all(
                            '/\{% subFor (\w+) in (\w+) %}(.*?)\{% endSubFor %}/s',
                            $renderedItemBlock,
                            $subMatches,
                            PREG_SET_ORDER
                        )) {

                            foreach ($subMatches as $subMatch) {

                                /**
                                 * Breakdown of sub-loop match:
                                 *
                                 * $fullSub     → entire sub-loop block
                                 * $subItemVar  → variable name (e.g. "meal")
                                 * $subArrayVar → property of parent item
                                 * $subBlock   → inner HTML of sub-loop
                                 */
                                [$fullSub, $subItemVar, $subArrayVar, $subBlock] = $subMatch;

                                /**
                                 * This string collects rendered
                                 * sub-loop iterations.
                                 */
                                $subReplacement = '';

                                /**
                                 * IMPORTANT:
                                 * Sub-loops read data ONLY from
                                 * the current parent item ($vars),
                                 * not from the root $data array.
                                 */
                                if (isset($vars[$subArrayVar]) && is_array($vars[$subArrayVar])) {

                                    foreach ($vars[$subArrayVar] as $subItem) {

                                        /**
                                         * Copy sub-loop content for
                                         * the current iteration.
                                         */
                                        $subBlockRendered = $subBlock;

                                        /**
                                         * Convert sub-item to array
                                         * for variable replacement.
                                         */
                                        $subVars = is_object($subItem)
                                            ? get_object_vars($subItem)
                                            : (array)$subItem;

                                        /**
                                         * Replace placeholders inside sub-loop.
                                         */
                                        foreach ($subVars as $sk => $sv) {
                                            if (!is_array($sv) && !is_object($sv)) {
                                                $subBlockRendered =
                                                    str_replace('{{' . $sk . '}}', $sv, $subBlockRendered);
                                            }
                                        }

                                        /**
                                         * Append rendered sub-item
                                         * to sub-loop output.
                                         */
                                        $subReplacement .= $subBlockRendered;
                                    }
                                }

                                /**
                                 * Replace the entire sub-loop block
                                 * with its rendered content.
                                 */
                                $renderedItemBlock =
                                    str_replace($fullSub, $subReplacement, $renderedItemBlock);
                            }
                        }

                        /**
                         * Append fully rendered item block
                         * to the main loop replacement string.
                         */
                        $replacement .= $renderedItemBlock;
                    }
                }

                /**
                 * Replace the entire main loop block
                 * in the template with rendered content.
                 */
                $template = str_replace($fullMatch, $replacement, $template);
            }
        }

        /**
         * ------------------------------------------------------------
         * STEP 3: REPLACE SIMPLE PLACEHOLDERS
         * ------------------------------------------------------------
         *
         * Replace placeholders like {{title}}, {{header}}, etc.
         * Only scalar values are processed here.
         */
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $template = str_replace('{{' . $key . '}}', $value, $template);
            }
        }

        /**
         * Return final rendered HTML.
         */
        return $template;
    }
}
