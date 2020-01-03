---
author: Dave Kerr
type: posts
categories:
- C#
- WPF
- MVVM
date: "2012-01-18T03:11:00Z"
description: ""
draft: false
slug: funky-wpf-enumerations-and-the-combo-box
tags:
- C#
- WPF
- MVVM
title: Funky WPF - Enumerations and the Combo Box
---


<p class="MsoNormal">Binding a combo box to an enumeration in WPF is more work than it should be, creating an object data provider etc etc:</p>
<pre class="brush: xml;">&lt;Window.Resources&gt;
    &lt;ObjectDataProvider MethodName="GetValues"
        ObjectType="{x:Type sys:Enum}"
        x:Key="CharacterEnumValues"&gt;
        &lt;ObjectDataProvider.MethodParameters&gt;
            &lt;x:Type TypeName="Character" /&gt;
        &lt;/ObjectDataProvider.MethodParameters&gt;
    &lt;/ObjectDataProvider&gt;
&lt;/Window.Resources&gt;</pre>
<p class="MsoNormal">Followed by</p>
<pre class="brush: xml;">&lt;ComboBox SelectedItem="{Binding Character}"<br /> ItemsSource="{Binding <br />Source={StaticResource CharacterValues}} "/&gt;</pre>
<p class="brush: xml;">What a pain! I have just added 'EnumerationComboBox' to my Apex library - so now you can do this:</p>
<pre class="brush: xml;">&lt;!-- The combo box, bound to an enumeration. --&gt;
&lt;apexControls:EnumerationComboBox <br />SelectedEnumeration="{Binding Character}" /&gt;</pre>
<p class="MsoNormal"><span lang="EN-US">No need for an ObjectDataProvider, an items source or anything &ndash; and if you decorate enum&rsquo;s with the &lsquo;[Description]&rsquo; attribute, it&rsquo;ll use the description in the combo.</span></p>
<p class="MsoNormal"><span lang="EN-US">There&rsquo;s an article/download here for anyone who's interested:</span></p>
<p class="MsoNormal"><a href="http://www.codeproject.com/KB/WPF/enumcombobox.aspx">http://www.codeproject.com/KB/WPF/enumcombobox.aspx</a></p>

