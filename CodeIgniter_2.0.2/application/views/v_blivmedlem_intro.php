<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
<link rel="shortcut icon" href="/images/favicon.ico" />
</head>
<body>
<span id="tt">
<span ID="title" style="float: left;" onClick="window.location.href='/minside/';" title="Til min forside">K&Oslash;BENHAVNS<br>
F&Oslash;DEVAREF&AElig;LLESSKAB <span id="green">/ MEDLEMSSYSTEM</span></span>
<button class="form_button" style="float: right; margin-top:33px;" onClick="window.location.href='http://kbhff.dk';">G&Aring; TIL KBHFF</button>
<img src="/images/banner.jpg" alt="K&oslash;benhavns F&oslash;devare F&aelig;llesskab" width="800" height="188" border="0">
<h1><?php echo $heading;?><!--, trin 1/5--></h1>
<p>K&oslash;benhavns F&oslash;devaref&aelig;llesskab er en medlemsejet og -drevet indk&oslash;bsforening, der fokuserer p&aring; at tilbyde &oslash;kologiske, velsmagende, lokalt producerede og b&aelig;redygtige f&oslash;devarer i et s&aelig;sonbaseret udbud til priser, hvor alle kan v&aelig;re med.</p>
<img src="/images/pose.gif" alt="" width="300" height="225" border="0" align="right">
<p>Som f&aelig;llesskab handler vi kun p&aring; vegne af vores medlemmer og ikke p&aring; vegne af en profits&oslash;gende industri. Ethvert overskud i foreningen g&aring;r derfor til lavere f&oslash;devarepriser, udvikling af foreningen eller til godg&oslash;rende sociale projekter i byen omkring os. Denne f&aelig;lles indsats g&oslash;r det muligt at holde priser, der g&oslash;r, at alle kan have r&aring;d til at k&oslash;be &oslash;kologisk.			  </p>
<p>Som medlem af K&oslash;benhavns F&oslash;devaref&aelig;llesskab kan du f&aring; &oslash;kologiske f&oslash;devarer hver uge. Dette g&oslash;r du ved at abonnere p&aring; en ugentlig pose. En pose koster 100 kr og rummer typisk 6-8 forskellige slags lokalt produceret og s&aelig;sonbestemt &oslash;kologisk frugt og gr&oslash;nt.			  </p>
<p>Bestilling, betaling og afhentning af varer finder sted i f&oslash;devaref&aelig;llesskabets butikker. Som medlem er du tilknyttet en bestemt lokalafdeling, f.eks. Amager eller N&oslash;rrebro. Du kan blive overflyttet til en anden lokalafdeling, hvis du skulle &oslash;nske dette.</p>
<p>Som medlem bestemmer du selv hvor ofte du &oslash;nsker at bestille en pose med gr&oslash;ntsager. Du kan kun bestille gr&oslash;ntsager i din lokale butik. Bem&aelig;rk, at bestilling er lig betaling. Det vil sige, at du skal betale n&aring;r du bestiller dine gr&oslash;ntsager, da vi hver uge bruger pengene til at k&oslash;be den n&aelig;ste uges gr&oslash;ntsager.</p>
<h3>Hvad kr&aelig;ver det af mig?</h3>
<p>Alle kan v&aelig;re med i K&oslash;benhavns F&oslash;devaref&aelig;llesskab. <strong>Et medejerskab koster 100 kr.</strong></p>
<p>Alle medlemmer forpligter sig til at arbejde i foreningen 3 timer om m&aring;neden.</p>
<p><a href="http://kbhff.dk/om-kbhff/nyt-medlem/" target="_blank">L&aelig;s mere om KBHFF</a> - <a href="http://medlem.kbhff.dk/minside/betingelser/" target="_blank">L&aelig;s betingelser</a>.</p>
<p><b>Indmeldelse</b> sker ved at <a href="http://kbhff.dk/afdelinger/">m&oslash;de op i afdelingen</a> du vil v&aelig;re medlem af<!-- - et par afdelinger giver dog mulighed for on-line indmeldelse-->.</p>
<!--<button OnClick="location.href='/blivmedlem';"><span  style="text-decoration: none; font-size: 20px; font-weight: bold;">Online indmeldelse</span></button>-->
<br>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>