#!/bin/bash
APP=User
SRC=src
PLUF=../../pluf

app=${APP,,}

# Check source directory
if [ ! -d "${SRC}" ]; then
	mkdir -f "${SRC}"
fi
cd $SRC

# Check application directory
if [ ! -d "${APP}" ]; then
	mkdir "$APP"
fi

# Config file
if [ ! -d "$APP/conf" ]; then
	mkdir "$APP/conf"
fi
if [ ! -f "${APP}/conf/${app}.translate.php" ]; then
	touch "${APP}/conf/${app}.translate.php"
	echo "<?php \$cfg = array();\$cfg['template_folders'] = array();\$cfg['languages'] = array('fa','en');return \$cfg;" > "${APP}/conf/${app}.translate.php" 
	echo "Please check config file : ${APP}/conf/${app}.translate.php"
fi

# Localize file
if [ ! -d "$APP/locale" ]; then
	mkdir "$APP/locale"
fi
if [ -f "${APP}/locale/${app}.pot" ]; then
	rm -f "${APP}/locale/${app}.pot"
fi
touch "${APP}/locale/${app}.pot"


php "$PLUF/src/extracttemplates.php" \
	"${APP}/conf/${app}.translate.php" \
	./gettexttemplates
find ./ -iname "*.php" \
	-exec xgettext \
	-o $app.pot \
	-p $APP/locale \
	--from-code=UTF-8 -j --keyword --keyword=__ --keyword=_n:1,2 -L PHP {} \;
rm -fR ./gettexttemplates