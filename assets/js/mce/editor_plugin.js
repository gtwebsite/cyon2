(function() {
	tinymce.create('tinymce.plugins.CyonShortcodes', {
		init: function(d,e) {}, 
		createControl:function(d,e){
					if(d=="cyon_plugin"){
					
						d=e.createMenuButton( "cyon_plugin",{
							title:"Cyon Insert Shortcode",
							icons:false
							});
							
							var a=this;d.onRenderMenu.add(function(c,b){
								
								c=b.addMenu({title:"Boxes"});
										a.addImmediate(c,"Accordion", '[accordion title="Title Here"]Some content here.[/accordion]');
										a.addImmediate(c,"Box", '[box icon="" color="" close="no" title="Title Here" width="" align="" quote="no"]Some content here.[/box]' );
										a.addImmediate(c,"Tab Group",'[tabs]<br/>[tab title="Title Here" active="false"]Some content here.[/tab]<br/>[tab title="Title Here" active="false"]Some content here.[/tab]<br/>[/tabs]' );
										a.addImmediate(c,"Tab Content",'[tab title="Title Here" active="false"]Some content here.[/tab]' );
										a.addImmediate(c,"Toggle", '[toggle title="Title Here"]Some content here.[/toggle]' );
										a.addImmediate(c,"Tooltip", '[tip text="Some content here."]Hover here.[/tip]');
								
								c=b.addMenu({title:"Snippets"});
										a.addImmediate(c,"Blog List", '[blog excerpt="yes" thumbnail="yes" cols="" items="4" cat_id="1"]' );
										a.addImmediate(c,"Contact Form", '[contact email=""]Contact us for more information about our services[/contact]' );
										a.addImmediate(c,"Newsletter Form", '[newsletter name="yes" email=""]Get the latest tips, news, and special offers delivered to your inbox.[/newsletter]' );
										a.addImmediate(c,"Sitemap", '[sitemap]' );
										a.addImmediate(c,"Sub Pages", '[subpages excerpt="yes" thumbnail="no" id="" cols=""]' );
										a.addImmediate(c,"Testimonials", '[testimonials id="" style="list"]' );

								c=b.addMenu({title:"Media"});
										a.addImmediate(c,"Audio",'[audio src="" width=""]' );
										a.addImmediate(c,"Video",'[video src="" width="" height="" poster="" subtitles="" chapters=""]' );
										a.addImmediate(c,"Iframe", '[iframe width="100%" height="350" scroll="true" url=""]');
										a.addImmediate(c,"Map", '[map width="" height="350" zoom="14" long="" lat="" address="New York, USA"]Some content here.[/map]');

								c=b.addMenu({title:"Typo"});
										a.addImmediate(c,"Back to Top",'[backtotop style=""]' );
										a.addImmediate(c,"Button",'[button color="" size="" icon="" url="" title=""]Button Name[/button]' );
										a.addImmediate(c,"Code",'[code inline="yes"][/code]' );
										a.addImmediate(c,"Header",'[header size="" icon=""]Title Here[/header]' );
										a.addImmediate(c,"Horizontal Line",'[line style=""]' );
										a.addImmediate(c,"Icon",'[icon element="" icon="" title="" url="" size=""]Text here[/icon]' );
										a.addImmediate(c,"Lists",'[lists icon="" cols="" size=""]<br/>[list icon=""]Item name[/list]<br/>[list icon=""]Item name[/list]<br/>[/lists]' );

								c=b.addMenu({title:"Table"});
										a.addImmediate(c,"Table",'[table caption="" headers="Column 1|Column 2" footers="Footer 1|Footer 2"]<br/>[row color=""]<br/>[data color=""]Data 1[/data]<br/>[data color=""]Data 2[/data]<br/>[/row]<br/>[/table]' );
										a.addImmediate(c,"Table Row",'[row color=""]<br/>[data color=""]Data[/data]<br/>[/row]' );
										a.addImmediate(c,"Table Data",'[data color="" rows="" cols=""]Data[/data]' );

								c=b.addMenu({title:"Columns"});
										a.addImmediate(c,"One Half","[one_half][/one_half]" );
										a.addImmediate(c,"One Half Last","[one_half_last][/one_half_last]" );
										a.addImmediate(c,"One Third","[one_third][/one_third]" );
										a.addImmediate(c,"One Third Last","[one_third_last][/one_third_last]" );
										a.addImmediate(c,"Two Third","[two_third][/two_third]" );
										a.addImmediate(c,"Two Third Last","[two_third_last][/two_third_last]" );
										a.addImmediate(c,"One Fourth","[one_fourth][/one_fourth]" );
										a.addImmediate(c,"One Fourth Last","[one_fourth_last][/one_fourth_last]" );
										a.addImmediate(c,"Three Fourth","[three_fourth][/three_fourth]" );
										a.addImmediate(c,"Three Fourth Last","[three_fourth_last][/three_fourth_last]" );
										a.addImmediate(c,"One Fifth","[one_fifth][/one_fifth]" );
										a.addImmediate(c,"One Fifth Last","[one_fifth_last][/one_fifth_last]" );
										a.addImmediate(c,"Two Fifth","[two_fifth][/two_fifth]" );
										a.addImmediate(c,"Two Fifth Last","[two_fifth_last][/two_fifth_last]" );
										a.addImmediate(c,"Three Fifth","[three_fifth][/three_fifth]" );
										a.addImmediate(c,"Three Fifth Last","[three_fifth_last][/three_fifth_last]" );
										a.addImmediate(c,"Four Fifth","[four_fifth][/four_fifth]" );
										a.addImmediate(c,"Four Fifth Last","[four_fifth_last][/four_fifth_last]" );
										a.addImmediate(c,"One Sixth","[one_sixth][/one_sixth]" );
										a.addImmediate(c,"One Sixth Last","[one_sixth_last][/one_sixth_last]" );
										a.addImmediate(c,"Five Sixth","[five_sixth][/five_sixth]" );
										a.addImmediate(c,"Five Sixth Last","[five_sixth_last][/five_sixth_last]" );
										a.addImmediate(c,"One Seventh","[one_seventh][/one_seventh]" );
										a.addImmediate(c,"One Seventh Last","[one_seventh_last][/one_seventh_last]" );
										a.addImmediate(c,"One Eighth","[one_eighth][/one_eighth]" );
										a.addImmediate(c,"One Eighth Last","[one_eighth_last][/one_eighth_last]" );
/*
								c=b.addMenu({title:"Columns Visualize"});
										a.addImmediate(c,"2 Columns", '<div class="row-fluid"><div class="span6"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div><div class="span6"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div></div><br>' );
										a.addImmediate(c,"3 Columns", '<div class="row-fluid"><div class="span4"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div><div class="span4"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div><div class="span4"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div></div><br>' );
										a.addImmediate(c,"4 Columns", '<div class="row-fluid"><div class="span3"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div><div class="span3"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div><div class="span3"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div><div class="span3"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div></div><br>' );
*/
								c=b.addMenu({title:"Price Grid"});
										a.addImmediate(c,"New Price Grid",'[pricegrid labels="Setup,Updates" columns="2" bgcolor="#03BCEE"]<br/>[gridcolumn title="Starter" price="$0.00" period="Yearly" link_url="http://" highlighted="yes" best_value="Yes"]<br/>[gridoption text="$0.00" tooltip_title="" tooltip_text="" /]<br/>[/gridcolumn]<br/>[/pricegrid]' );
										a.addImmediate(c,"Column Normal",'[gridcolumn title="Starter" price="$0.00" period="Yearly" link_url="http://" highlighted="yes" best_value="Yes"]<br/>[gridoption text="$0.00" tooltip_title="" tooltip_text="" /]<br/>[/gridcolumn]' );
										a.addImmediate(c,"Column Highlighted",'[gridcolumn title="Starter" price="$0.00" period="Yearly" link_url="http://"]<br/>[gridoption text="$0.00" tooltip_title="" tooltip_text="" /]<br/>[/gridcolumn]' );
										a.addImmediate(c,"Option Text",'[gridoption text="$0.00" tooltip_title="" tooltip_text="" /]' );
										a.addImmediate(c,"Option Check",'[gridoption checked="yes" tooltip_title="" tooltip_text="" /]' );
								

							});
						return d
					
					} // End IF Statement
					
					return null
		},
		addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}
	});

	// Register plugin
	tinymce.PluginManager.add('cyon_plugin', tinymce.plugins.CyonShortcodes);
})();