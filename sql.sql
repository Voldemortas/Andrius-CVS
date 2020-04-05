BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS `log` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`ip`	TEXT NOT NULL,
	`url`	TEXT NOT NULL,
	`error`	INTEGER,
	`user`	INTEGER NOT NULL,
	`time`	TEXT NOT NULL,
	FOREIGN KEY(`user`) REFERENCES `user`(`id`)
);

CREATE TABLE IF NOT EXISTS `role` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	TEXT NOT NULL UNIQUE
);
INSERT INTO `role` (id,name) VALUES (0,'Neprisijungęs'),
 (1,'Prisijungęs'),
 (2,'Patvirtintas'),
 (3,'Adminas');
CREATE TABLE IF NOT EXISTS `user` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`email`	TEXT NOT NULL UNIQUE,
	`password`	TEXT NOT NULL,
	`roles`	INTEGER NOT NULL DEFAULT 0
);
CREATE TABLE IF NOT EXISTS `page` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`title`	TEXT NOT NULL,
	`url`	TEXT NOT NULL UNIQUE,
	`roles`	INTEGER DEFAULT 0
);
INSERT INTO `page` (id,title,url,roles) VALUES (1,'Naujienos','news',0),
 (2,'Prisijungti','login',1),
 (3,'Registruotis','register',1),
 (4,'Apie','about',0),
 (5,'Dažniausiai užduodami klausimai','faq',0),
 (6,'Valdymo panelė','admin',4),
 (7,'Peradreavimas','redirect',0),
 (8,'Nustatymai','settings',-1),
 (9,'Atsijungti','logout',-1);
CREATE TABLE IF NOT EXISTS `news` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`title`	TEXT NOT NULL,
	`text`	TEXT NOT NULL,
	`url`	TEXT NOT NULL UNIQUE,
	`roles`	INTEGER NOT NULL DEFAULT 0,
	`nonprivate`	INTEGER NOT NULL DEFAULT 0
);
INSERT INTO `news` (id,title,text,url,roles,nonprivate) VALUES (1,'Sveikas, pasauli!','<p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis lectus sed neque euismod euismod. Aliquam et lorem sit amet urna dignissim fermentum. Cras id sagittis lectus, nec facilisis velit. Nunc est est, ornare ac est non, varius tempor dui. Sed a tortor vitae urna suscipit ornare ac eu diam. Quisque at felis non libero pharetra ornare. Quisque augue lectus, bibendum in gravida tincidunt, bibendum non sem. Nam eu lobortis enim, nec facilisis nisl. Pellentesque et neque sit amet velit consequat auctor fringilla nec lectus.
    </p>
    <p>
        Nulla facilisi. Sed rutrum rhoncus augue nec bibendum. Donec cursus quam ac erat congue sodales. Fusce dui enim, sagittis ut malesuada ornare, sagittis non nisi. Vestibulum placerat dui id nibh mattis, ac eleifend est luctus. Nunc a eros eu eros tincidunt interdum vel et odio. Cras pulvinar placerat massa, ac ultrices ipsum lacinia nec.
    </p>
    <p>
        Phasellus varius porttitor accumsan. Sed egestas, mi sit amet sollicitudin pretium, turpis urna egestas lorem, id lobortis lorem arcu eget nisi. Integer viverra facilisis ligula ut tempor. Maecenas vulputate elementum velit, quis feugiat nunc varius sed. Aenean vitae nisi arcu. Proin quis ligula quis lorem hendrerit laoreet. Nullam enim libero, dignissim nec augue sit amet, tempus lacinia ex. Pellentesque convallis metus massa, egestas volutpat lacus bibendum vitae. Vestibulum lacinia auctor nibh, nec pellentesque libero congue vitae. Curabitur vel ullamcorper purus. Vestibulum pharetra egestas odio sodales condimentum. Vivamus id auctor tellus. Nullam tempor leo et ullamcorper pellentesque.
    </p>
    <p>
        Phasellus maximus erat ante, id imperdiet velit rutrum elementum. Vestibulum volutpat ligula quis faucibus consectetur. Quisque dignissim lacinia diam eu bibendum. Proin volutpat, neque ac pulvinar dictum, augue magna pulvinar sapien, ac porta augue mauris sit amet eros. Praesent in tincidunt nibh. Ut at velit in lorem egestas egestas id sit amet dolor. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nullam dui ex, varius in ultricies eu, finibus nec sapien. Nam vel risus aliquet, varius lacus eu, facilisis libero. Ut eget augue fringilla, consectetur enim eu, semper mi. Ut ac gravida est, non aliquam dui. Vestibulum maximus nibh in gravida accumsan. Cras neque erat, dignissim ut felis eu, faucibus fringilla augue. Praesent porta luctus magna eu viverra. Curabitur pretium neque pulvinar hendrerit fermentum. Aenean et egestas ex, id vehicula nibh.
    </p>
    <p>
        Duis tortor purus, feugiat et tincidunt id, finibus non elit. Donec at vestibulum mauris. Quisque maximus consectetur sapien bibendum tempus. Maecenas venenatis ornare mauris, at pretium felis convallis vitae. Aliquam erat volutpat. Nunc at faucibus magna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce pretium, nulla vel cursus fringilla, velit enim ornare risus, nec tempor odio arcu in arcu. Curabitur pulvinar justo sit amet ante commodo, in blandit nisl commodo.
    </p>','hello_world',0,0),
 (2,'Šitą mato tik prisijungę','[html]  [/html]Nulla volutpat quam in felis tincidunt mollis. Praesent blandit, odio at pretium dignissim, ligula mi maximus sem, et egestas mi orci in nisi. Sed tincidunt congue dolor vel lobortis. Curabitur at ultricies mauris. Duis at erat dapibus, dictum lacus vel, fermentum arcu. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi rhoncus purus id tortor convallis, sit amet laoreet metus semper. Curabitur facilisis ligula quis cursus imperdiet. Fusce accumsan fermentum nisl vitae tincidunt. Duis vitae ipsum non justo porttitor scelerisque quis sed leo.

[html]  [/html]Nullam eget dolor quis est auctor pellentesque. Donec ipsum ipsum, cursus eget facilisis ut, efficitur non tortor. Nullam risus erat, congue a scelerisque nec, consectetur et tortor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aliquam erat volutpat. Pellentesque quis ligula et tellus euismod euismod. Fusce tincidunt lectus vitae aliquet gravida. Maecenas tempus magna sit amet urna vehicula, id condimentum orci tincidunt. Sed at vulputate nibh.

[html]  [/html]Nullam ut dolor pulvinar, imperdiet mauris gravida, facilisis turpis. Sed semper, eros non hendrerit bibendum, enim lacus commodo ipsum, et accumsan velit justo sed lacus. Praesent cursus imperdiet purus, quis faucibus felis mollis ultricies. Sed scelerisque convallis elit, ut tristique felis congue vitae. Cras fringilla vehicula ex, bibendum porttitor elit vulputate ut. Curabitur scelerisque sapien at posuere aliquam. Nam nulla ante, cursus sit amet auctor sagittis, rhoncus a sapien. Ut vitae dolor quis nisi mattis finibus. Quisque in varius nunc. Praesent tincidunt gravida convallis. Morbi sed urna pulvinar, pulvinar ex nec, pharetra ex. Cras lobortis sem magna, sit amet pulvinar turpis sollicitudin sit amet. Duis eu ligula dapibus nisi suscipit sodales. Vivamus nec leo laoreet, fringilla purus in, sagittis mi.


[html]  [/html]Etiam eget velit ac odio consequat suscipit eu eu risus. In convallis bibendum interdum. Duis bibendum lobortis tristique. Suspendisse nibh nunc, gravida a magna non, dapibus fermentum nibh. Vestibulum ut eleifend leo, vitae porttitor risus. Duis ut ipsum nec tortor maximus tincidunt. Nam sagittis lorem id sem ullamcorper, eget dictum enim tristique. Integer imperdiet urna id lacinia ullamcorper. Etiam venenatis, turpis sed ullamcorper fermentum, mauris nisi mollis ante, at pharetra metus mi ut augue. Suspendisse potenti. Nullam elementum sit amet neque vitae blandit. Aliquam et dolor commodo, hendrerit magna vel, congue elit. Praesent eget urna vitae lectus facilisis sodales sit amet sed eros. Aenean at elit efficitur, sollicitudin ante in, lacinia sem. Nulla at urna luctus, laoreet dolor a, porttitor lacus.

[html]  [/html]Aliquam erat volutpat. Fusce sem metus, mollis vel justo at, molestie consectetur sem. Praesent eleifend diam non lectus viverra tempus. Ut nec pulvinar mauris. Nullam maximus fringilla turpis. Morbi gravida condimentum augue, sit amet rutrum turpis tempor vel. Quisque ultricies quis erat at eleifend. Vestibulum ut purus dictum, eleifend ante ac, interdum lectus. Integer fringilla sem sit amet ultrices efficitur. Pellentesque lacus nisi, condimentum in nisl eget, pulvinar semper lorem. Curabitur porttitor mi porttitor orci placerat tincidunt. Nulla aliquam massa in lobortis varius. Aenean neque augue, lacinia vitae arcu non, vestibulum tincidunt massa. Ut porttitor ante a consequat feugiat. Sed lobortis, turpis ultricies hendrerit pellentesque, lorem felis tempor nunc, et fringilla nisi tellus quis est.','logged-in',-1,0),
 (3,'Neviešas įrašas','[b][colour silver]/ɫaːbɐs pɐsaˑʊlʲɪ/[/colour][/b]','private',0,1);
CREATE TABLE IF NOT EXISTS `submenu` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`text`	TEXT NOT NULL,
	`url`	TEXT NOT NULL,
	`roles`	INTEGER DEFAULT 0,
	`menu`	INTEGER NOT NULL,
	FOREIGN KEY(`menu`) REFERENCES `menu`(`id`)
);
INSERT INTO `submenu` (id,text,url,roles,menu) VALUES (1,'Apie','about',0,2),
 (2,'DUK','faq',0,2);
CREATE TABLE IF NOT EXISTS `menu` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`text`	TEXT NOT NULL,
	`url`	TEXT NOT NULL,
	`roles`	INTEGER DEFAULT 0
);
INSERT INTO `menu` (id,text,url,roles) VALUES (1,'Naujienos','news',0),
 (2,'Informacija','#',0),
 (3,'Registruotis','register',1),
 (4,'Prisijungti','login',1),
 (5,'Valdymo skydas','admin',4),
 (6,'Paskyra','settings',-1),
 (7,'Atsijungti','logout/',-1);
CREATE TABLE IF NOT EXISTS `website` (
	`name`	TEXT NOT NULL,
	`logo`	TEXT DEFAULT '',
	`url`	TEXT NOT NULL
);
COMMIT;