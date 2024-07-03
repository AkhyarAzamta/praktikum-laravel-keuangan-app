:local mac $"mac-address";
:set mac [:ip dhcp-server lease get [:ip dhcp-server lease find mac-address="$mac"] host];
:local nama "$user";
:local ips [/ip hotspot active get [find user="$nama"] address];
:local exp [/ip hotspot user get [find name="$nama"] comment];
:local profile [/ip hotspot user get [find name="$nama"] profile];
:local datetime "$[/system clock get date] $[/system clock get time]";
:local mac [/ip hotspot active get [find user="$nama"] mac-address];
:local host [/ip dhcp-server lease get [find address="$ips"] host-name];
:local lby [/ip hotspot active get [find user="$nama"] login-by];
:local limit [/ip hotspot active get [find user="$nama"] limit-bytes-total];
:local totq [(($limit)/1048576)];
:local useraktif [/ip hotspot active print count-only];
:tool fetch url="https://api.telegram.org/bot6724015820:AAErJKoB8FJhz9nSzWYM2QbGcuuA8lZVaZQ/sendMessage?chat_id=1994541524&text===>>INFO LOGIN<<==%0A- Kode Voucher : $nama%0A- IP Address : $ips %0A- Mac Address : $mac%0A- User : $host%0A- Metode Login : $lby%0A- Kuota : $totq Mb%0A- Expired Voucher : $exp%0A- Waktu Login : $datetime%0A- Paket : $profile%0A- User Online : $useraktif user" mode=http keep-result=no;


:local host [/ip dhcp-server lease get [find address="$ips"] host-name];

:tool fetch url="https://api.telegram.org/bot6724015820:AAErJKoB8FJhz9nSzWYM2QbGcuuA8lZVaZQ/sendMessage?chat_id=1994541524&text=<<==INFO LOGOUT==>>%0A- Kode Voucher : $user%0A- IP Address : $address" keep-result=no;