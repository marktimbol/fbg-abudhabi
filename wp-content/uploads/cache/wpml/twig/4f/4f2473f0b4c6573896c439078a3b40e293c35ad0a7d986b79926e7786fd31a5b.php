<?php

/* source-language.twig */
class __TwigTemplate_d011d77f270d1f5b498e260ff5cff20b1c4acc10ed5a27e332e428b9290ada2c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"source_language\">
\t<label for=\"source-language-selector\">";
        // line 2
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "sourceLanguageSelectorLabel", array()), "html", null, true);
        echo ":</label>
\t<select id=\"source-language-selector\">
\t\t";
        // line 4
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["activeLanguages"]) ? $context["activeLanguages"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["activeLanguage"]) {
            // line 5
            echo "\t\t\t";
            $context["default"] = ($this->getAttribute($context["activeLanguage"], "code", array()) == (isset($context["defaultLanguage"]) ? $context["defaultLanguage"] : null));
            // line 6
            echo "\t\t\t";
            $context["showTranslated"] = ($this->getAttribute($context["activeLanguage"], "native_name", array(), "array") != $this->getAttribute($context["activeLanguage"], "translated_name", array(), "array"));
            // line 7
            echo "\t\t\t";
            $context["language"] = (((isset($context["showTranslated"]) ? $context["showTranslated"] : null)) ? (((($this->getAttribute($context["activeLanguage"], "translated_name", array(), "array") . " (") . $this->getAttribute($context["activeLanguage"], "native_name", array(), "array")) . ")")) : ($this->getAttribute($context["activeLanguage"], "native_name", array(), "array")));
            // line 8
            echo "\t\t\t<option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["activeLanguage"], "code", array()));
            echo "\"";
            if ((isset($context["default"]) ? $context["default"] : null)) {
                echo " selected=\"selected\" ";
            }
            echo ">";
            echo twig_escape_filter($this->env, (isset($context["language"]) ? $context["language"] : null), "html", null, true);
            echo "</option>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['activeLanguage'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 10
        echo "\t</select>
\t<input type=\"hidden\" name=\"wpml_words_count_source_language_nonce\" value=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["nonces"]) ? $context["nonces"] : null), "wpml_words_count_source_language_nonce", array()));
        echo "\">
</div>";
    }

    public function getTemplateName()
    {
        return "source-language.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  58 => 11,  55 => 10,  40 => 8,  37 => 7,  34 => 6,  31 => 5,  27 => 4,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "source-language.twig", "/home/content/n3pnexwpnas02_data03/03/3938603/html/wp-content/plugins/wpml-translation-management/templates/words-count/source-language.twig");
    }
}
