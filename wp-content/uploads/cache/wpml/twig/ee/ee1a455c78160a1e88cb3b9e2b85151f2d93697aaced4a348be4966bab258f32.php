<?php

/* status.twig */
class __TwigTemplate_18f318505c0fc305dac7193d3e63c79a2bd2d46a7409230b27859967b64851a8 extends Twig_Template
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
        echo (isset($context["conf_warnings"]) ? $context["conf_warnings"] : null);
        echo "

";
        // line 3
        echo (isset($context["store_pages"]) ? $context["store_pages"] : null);
        echo "

";
        // line 5
        echo (isset($context["products"]) ? $context["products"] : null);
        echo "

";
        // line 7
        echo (isset($context["taxonomies"]) ? $context["taxonomies"] : null);
        echo "

";
        // line 9
        echo (isset($context["multi_currency"]) ? $context["multi_currency"] : null);
        echo "

";
        // line 11
        echo (isset($context["plugins_status"]) ? $context["plugins_status"] : null);
        echo "

<a class=\"alignright wpml-margin-top-sm\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, (isset($context["troubl_url"]) ? $context["troubl_url"] : null), "html", null, true);
        echo "\">
    ";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["strings"]) ? $context["strings"] : null), "troubl", array()), "html", null, true);
        echo "
</a>";
    }

    public function getTemplateName()
    {
        return "status.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 14,  49 => 13,  44 => 11,  39 => 9,  34 => 7,  29 => 5,  24 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "status.twig", "/home/content/n3pnexwpnas02_data03/03/3938603/html/wp-content/plugins/woocommerce-multilingual/templates/status/status.twig");
    }
}
