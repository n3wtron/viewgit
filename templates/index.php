
<table>
<thead>
<tr>
	<th>Project</th>
	<th>Description</th>
	<th>Last Change</th>
	<th>Actions</th>
</tr>
</thead>
<tbody>
<?php
foreach ($page['projects'] as $p) {
	$tr_class = $tr_class=="odd" ? "even" : "odd";
	echo "<tr class=\"$tr_class\">\n";
	echo "\t<td><a href=\"". makelink(array('a' => 'summary', 'p' => $p['name'])) ."\">$p[name]</a></td>\n";
	echo "\t<td>". htmlentities_wrapper($p['description']) ."</td>\n";
	echo "\t<td>". htmlentities_wrapper($p['head_datetime']) ."</td>\n";
	echo "\t<td>";
	echo "<a href=\"". makelink(array('a' => 'tree', 'p' => $p['name'], 'h' => $p['head_tree'], 'hb' => $p['head_hash'])) ."\" class=\"tree_link\" title=\"Tree\">tree</a>";
	echo " <a href=\"". makelink(array('a' => 'archive', 'p' => $p['name'], 'h' => $p['head_tree'], 'hb' => $p['head_hash'], 't' => 'targz')) ."\" rel=\"nofollow\" class=\"tar_link\" title=\"tar/gz\">tar/gz</a>";
	echo " <a href=\"". makelink(array('a' => 'archive', 'p' => $p['name'], 'h' => $p['head_tree'], 'hb' => $p['head_hash'], 't' => 'zip')) ."\" rel=\"nofollow\" class=\"zip_link\" title=\"zip\">zip</a>";
	echo " <a href=\"graph/viewgraph.php".makelink(array('project'=>$p['name'],'path'=>$p['repo'],'short'=>'1'))."\" rel=\"nofollow\" title=\"Last 3 months History Graph\" class=\"short_graph\">short_graph</a>";
	echo " <a href=\"graph/viewgraph.php".makelink(array('project'=>$p['name'],'path'=>$p['repo']))."\" rel=\"nofollow\" title=\"Full History Graph\" class=\"full_graph\">full_graph</a>";
	echo "</td>\n";
	echo "</tr>\n";
}
?>
</tbody>
</table>

