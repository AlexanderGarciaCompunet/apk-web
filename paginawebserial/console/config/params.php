<?php
$sqlQuery1 = <<<'EOT'
select a.client_id, a.ordnum,a.ordlin,a.prtnum,a.appqty,c.stkuom
  from {database}.shipping_pckwrk_view a inner
  join {database}.trlr k
  on (a.trlr_id = k.trlr_id) 
  inner join {database}.ord b
  on (b.wh_id = a.wh_id
  and b.client_id = a.client_id
  and b.ordnum = a.ordnum) 
  inner join {database}.prtmst c
  on (c.wh_id_tmpl = a.wh_id
  and c.prt_client_id = a.client_id
  and c.prtnum = a.prtnum)
  where to_char(k.dispatch_dte, 'YYYYMMDD') between '20160701' and '20210715'
  and a.prt_client_id in ('TELMEX','TELDEV','TELMOV')
  and k.trlr_cod = 'SHIP'
  and a.pcksts = 'C'
  and a.appqty > 0
EOT;

$sqlCustomers = <<<'EOT'
select a.client_id,
        c.lngdsc
   from {database}.client_grp_client a,
        {database}.client_grp b,
        {database}.dscmst c
  WHERE a.client_grp = b.client_grp
    and c.colnam = 'client_grp'
    and c.colval = a.client_grp
    and c.locale_id = 'ES-ES'
EOT;

$sqlStores = <<<'EOT'
select wh.wh_id,
        c.locale_id,
        c.lngdsc
    from {database}.wh,
        {database}.dscmst c
    where c.colnam = 'wh_id'
        and c.colval = wh.wh_id
        and c.locale_id = 'ES-ES'
EOT;


$sqlSerialized = <<<'EOT'
SELECT
	a.wh_id_tmpl,
	a.prt_client_id,
	a.prtnum,
	i.lngdsc,
	b.uomcod,
	b.len,
	b.wid,
	b.hgt,
	b.grswgt,
	b.netwgt
FROM
	{database}.prtmst a,
	{database}.prtftp h,
	{database}.prtdsc i,
	{database}.prtftp_dtl b
WHERE
	a.prt_client_id IN ({clients})
	AND h.wh_id = a.wh_id_tmpl
	AND h.prtnum = a.prtnum
	AND h.prt_client_id = a.prt_client_id
	AND i.colnam = 'prtnum|prt_client_id|wh_id_tmpl'
	AND i.colval = a.prtnum || '|' || a.prt_client_id || '|' || a.wh_id_tmpl
	AND i.locale_id = 'ES-ES'
	AND b.prtnum = h.prtnum
	AND b.ftpcod = h.ftpcod
	AND b.prt_client_id = h.prt_client_id
	AND b.rcv_flg = 1
	AND a.ser_typ IS NOT NULL
EOT;




// ({clients})

$sqlNotSerialized = <<<'EOT'
select a.wh_id_tmpl,
        a.prt_client_id,
        a.prtnum,
        i.lngdsc,
        b.uomcod,
        b.len,
        b.wid,
        b.hgt,
        b.grswgt,
        b.netwgt
   from {database}.prtmst a,
        {database}.prtftp h,
        {database}.prtdsc i,
        {database}.prtftp_dtl b
  where a.prt_client_id = 'TELMEX'
    and a.wh_id_tmpl = '718'
    and h.wh_id = a.wh_id_tmpl
    and h.prtnum = a.prtnum
    and h.prt_client_id = a.prt_client_id
    and i.colnam = 'prtnum|prt_client_id|wh_id_tmpl'
    and i.colval = a.prtnum || '|' || a.prt_client_id || '|' || a.wh_id_tmpl
    and i.locale_id = 'ES-ES'
    and b.prtnum = h.prtnum
    and b.ftpcod = h.ftpcod
    and b.prt_client_id = h.prt_client_id
    and b.rcv_flg = 1
    and a.prtfam = 'NO_SERIAL'
EOT;

$sqlOrdered = <<<'EOT'
select c.wh_id,
        c.client_id,
        c.invnum,
        c.invlin,
        c.prtnum,
        c.inv_attr_str2 || '-' || c.inv_attr_str4 orgcod,
        sum(c.expqty) expqty,
        c.rcvsts,
        min(d.uomcod)
   from {database}.trlr a
  inner
   join {database}.rcvtrk b
     on a.trlr_id = b.trlr_id
  inner
   join {database}.rcvlin c
     on b.trknum = c.trknum
    and b.wh_id = c.wh_id
  inner
   join {database}.prtftp p
     on c.prtnum = p.prtnum
    and c.wh_id = p.wh_id
    and c.prt_client_id = p.prt_client_id
    and c.client_id in ({clients})
  inner
   join {database}.prtftp_dtl d
     on p.prtnum = d.prtnum
    and p.wh_id = d.wh_id
    and p.prt_client_id = d.prt_client_id
    and p.ftpcod = d.ftpcod
  where a.trlr_stat = 'EX'
    and a.trlr_cod = 'RCV'
    and c.expqty > 0
    and a.moddte > sysdate -15
    and p.defftp_flg = 1
    and d.rcv_flg = 1    
  group by c.wh_id,
        c.client_id,
        c.invnum,
        c.invlin,
        c.prtnum,
        c.orgcod,
        c.rcvsts,
        c.inv_attr_str2,
        c.inv_attr_str4,
        d.uomcod

EOT;


$sqlReceived = <<<'EOT'
select c.wh_id,
c.client_id,
c.invnum,
c.prtnum,
c.orgcod,
c.invlin,
sum(c.expqty) expqty,
c.rcvsts,
min(d.uomcod)
from {database}.trlr a
inner
join {database}.rcvtrk b
on a.trlr_id = b.trlr_id
inner
join {database}.rcvlin c
on b.trknum = c.trknum
and b.wh_id = c.wh_id
inner
join {database}.prtftp_dtl d
on c.prtnum = d.prtnum
and c.wh_id = d.wh_id
and c.prt_client_id = d.prt_client_id
where a.trlr_stat = 'EX'
and a.trlr_cod = 'RCV'
and c.expqty > 0
and a.moddte > sysdate -120
and d.rcv_flg = 1
group by c.wh_id,
c.client_id,
c.invnum,
c.invlin,
c.prtnum,
c.orgcod,
c.rcvsts,
d.uomcod
EOT;

$sqlVersion = <<<'EOT'
select * from v$version;
EOT;

return [
  'adminEmail' => 'admin@example.com',
  'supportEmail' => 'support@example.com',
  'user.passwordResetTokenExpire' => 3600,
  'sqlQuery1' => $sqlQuery1,
  'sqlCustomers' => $sqlCustomers,
  'sqlStores' => $sqlStores,
  'sqlSerialized' => $sqlSerialized,
  'sqlNotSerialized' => $sqlNotSerialized,
  'sqlOrdered' => $sqlOrdered,
  'sqlReceived' => $sqlReceived,
  'sqlVersion' => $sqlVersion,
];
