#!/bin/bash
PRJ=$1
PATH=$2
. git2dot.config

cd $PATH
if [ $# -eq 3 ];then
	GIT_OPTS="$GIT_OPTS --after=$3"
fi

echo "digraph $PRJ{"
echo "rankdir=LR;"
echo "node [style=filled,fillcolor=aquamarine, color=black, fontsize=8, width=0.3, height=0.3];"

while read fh;do
	H=""
	P=""
	H=$(echo $fh| ${AWK} -F';' '{print $1}')
	P=$(echo $fh| ${AWK} -F';'  '{print $2}')
	let np=0
	if [ "${P}x" == "x" ];then
		DT=$(echo $fh| ${AWK} -F';'  '{print $3}')
	else
		for parent in $P; do	
			DT=$(${GIT} log -n 1 --pretty=%aD $parent)
			let np=$np+1	
		done
	fi
	M=$(echo $DT| ${AWK} '{print $3}')
	Y=$(echo $DT| ${AWK} '{print $4}')
	SM="$M-$Y"
	A=$(echo $fh| ${AWK} -F';' '{print $4}')
	S=$(echo $fh| ${AWK} -F';' '{print $5}')
	T=$(echo $fh| ${AWK} -F';' '{print $6}')
	AUTHOR=$(echo $fh| ${AWK} -F';' '{print $7}')
	T="$T"
	if [ "$SM" != "$OSM" ];then
		if [ "${OSM}x" != "x" ];then
			echo "}"	
		fi
		echo "subgraph cluster_${M}_${Y} {"
		echo "label=\"$SM\";"	
		echo "style=filled;"
		echo "color=azure2;"
		OSM=$SM
	fi
	if [ $np -gt 1 ];then
		#multiple parent
		FILLCOLOR="red"
	else
		FILLCOLOR="aquamarine"
	fi
	if [[ "$T" =~ "MTS [0-9]{1,}[ ]*:[ ]*[A-Z|a-z]{1,}" ]]; then
		MTS=$(echo $T | ${AWK} '{print $2}'|${SED} 's/://g')
	
		if [ "${A}x" != "x" ];then
			LABEL="<<table border=\"0\"><tr><td>${A}</td></tr><tr><td HREF=\"$MANTIS/view.php?id=$MTS\">MTS</td></tr></table>>"
		else
			LABEL="<<table border=\"0\"><tr><td HREF=\"$MANTIS/view.php?id=$MTS\">M</td></tr></table>>"
		fi
	else
		LABEL="\"${A}\""
	fi
	T="$T($AUTHOR)"
	T=$(echo $T | ${SED} 's/"/\\"/g')
	if [ "${A}x" != "x" ];then
		echo "\"$H\" [ shape=box, fillcolor=$FILLCOLOR, label=$LABEL, URL=\"${VIEW_GIT}/?a=commitdiff&p=$PRJ&h=$H\", tooltip=\"$T\"];"
	else
		echo "\"$H\" [ label=$LABEL, fillcolor=$FILLCOLOR, tooltip=\"$T\", URL=\"${VIEW_GIT}/?a=commitdiff&amp;p=$PRJ&amp;h=$H\"];"
	fi
	if [ "${P}x" != "x" ];then
		for parent in $P; do	
			echo "\"$parent\"->\"$H\";"
		done
	fi
done <<EOF
$(${GIT} log --all --pretty="%H;%P;%aD;%d;%h (%d);%s;%aN" $GIT_OPTS)
EOF
echo "}"
echo "}"

