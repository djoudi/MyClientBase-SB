How to create a .po file by parsing a template file .tpl


First of all I create the .c file containing all the string to be translated by parsing a .tpl file with this command
php -q tsmarty2c.php ../../../views/index.tpl > text.c


Then I create the .po file with this command
xgettext -o it_IT/LC_MESSAGES/messages.po --omit-header --no-location text.c

If the .po file already exist and I want to update its content I use this command instead
xgettext -o it_IT/LC_MESSAGES/messages.po --omit-header --join-existing --no-location text.c

Then I edit the .po file and I add the translated content

Finally I create the .mo file by running
cd locale/it_IT/LC_MESSAGES/
msgfmt -o messages.mo messages.po
