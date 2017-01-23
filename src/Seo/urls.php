<?php
return array(
        array(
                'regex' => '#^/sitemap/sitemap.xml$#',
                'model' => 'Seo_Views_Sitemap',
                'method' => 'get',
                'http-method' => 'GET',
                'precond' => array()
        ),
        array(
                'regex' => '#^/xsl/(?P<style>[^/]+).xsl$#',
                'model' => 'Seo_Views_Xsl',
                'method' => 'get',
                'http-method' => 'GET',
                'precond' => array()
        )

);