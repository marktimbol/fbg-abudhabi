<?php

/* box.twig */
class __TwigTemplate_23af01e8050f1b37bbc8394500080035dc60e3b7bfcc9a77a7362e0bde1c3a23 extends Twig_Template
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
        echo "<div class=\"wpml-accordion wpml-wc-accordion\">
\t<h4 class=\"handle\"><span>";
        // line 2
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "title", array()), "html", null, true);
        echo "</span></h4>

\t<div class=\"inside\">
\t\t<input type=\"hidden\" name=\"wpml_words_count_panel_nonce\" value=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["nonces"]) ? $context["nonces"] : null), "wpml_words_count_panel_nonce", array()));
        echo "\">
\t\t<input type=\"hidden\" name=\"wpml_words_count_chunk_size\" value=\"";
        // line 6
        echo twig_escape_filter($this->env, (isset($context["wc_chunk_size"]) ? $context["wc_chunk_size"] : null));
        echo "\">

\t\t<div class=\"wpml-wc-messages\">
\t\t\t";
        // line 9
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "messages", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 10
            echo "\t\t\t\t<p>";
            echo twig_escape_filter($this->env, $context["message"], "html", null, true);
            echo "</p>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 12
        echo "\t\t</div>
\t\t<div class=\"wpml-wc-buttons\">
\t\t\t<p>
\t\t\t\t<a class=\"button-primary\" href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "openDialogButtonURL", array()), "html", null, true);
        echo "\" title=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "openDialogButton", array()));
        echo "\">
\t\t\t\t\t";
        // line 16
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "openDialogButton", array()), "html", null, true);
        echo "
\t\t\t\t</a>
\t\t\t</p>

\t\t\t<p class=\"call-to-action\">
\t\t\t\t";
        // line 21
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "callToAction", array()), "Text", array()), "html", null, true);
        echo " <a href=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "callToAction", array()), "linkURL", array()), "html", null, true);
        echo "\" target=\"_blank\" title=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "callToAction", array()), "linkText", array()));
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "callToAction", array()), "linkText", array()), "html", null, true);
        echo "</a>.
\t\t\t</p>
\t\t</div>
\t\t";
        // line 24
        $this->loadTemplate("dialog.twig", "box.twig", 24)->display(array_merge($context, (isset($context["dialog"]) ? $context["dialog"] : null)));
        // line 25
        echo "\t</div>
</div>";
    }

    public function getTemplateName()
    {
        return "box.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  84 => 25,  82 => 24,  70 => 21,  62 => 16,  56 => 15,  51 => 12,  42 => 10,  38 => 9,  32 => 6,  28 => 5,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "box.twig", "/home/content/n3pnexwpnas02_data03/03/3938603/html/wp-content/plugins/wpml-translation-management/templates/words-count/box.twig");
    }
}
