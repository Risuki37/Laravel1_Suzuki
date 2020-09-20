'ファイル書き込みを行うための記載
Dim fso
Set fso = WScript.CreateObject("Scripting.FileSystemObject")

' IP取得関数
' 引数: なし
' 戻り値: IP(オクテットごとに分けられたサイズ4の配列) の配列
Function getIpAddress()
   ' 戻り値用変数
    Dim ipAddressList

   ' 変数を動的配列にする
    Set ipAddressList = CreateObject("System.Collections.ArrayList")

   ' IPを調べたいホストの名前
    Const strComputer = "."

   ' WMIオブジェクトを取得
    Set wmiService = GetObject("winmgmts:\\" & strComputer & "\root\cimv2")

   ' ネットワークアダプタの情報を取得
    Set colItems = wmiService.ExecQuery("SELECT * FROM Win32_NetworkAdapterConfiguration WHERE IpEnabled=TRUE")

   ' ネットワークアダプタの情報からIPを取り出し、戻り値の配列に追加
    For Each item in colItems
        For Each ipAddress in item.IPAddress
            arrOctets = Split(ipAddress, ".")
            
            If (UBound(arrOctets) + 1) = 4 Then
               ' IPがIPv4の場合のみ追加する(IPv6はしない)
                ipAddressList.add arrOctets
            End If
        Next
    Next

   ' 戻り値は静的配列にする
    getIpAddress = ipAddressList.ToArray()
End Function

ipAddressList = getIpAddress()

Wscript.Echo (UBound(ipAddressList) + 1)
For Each ipAddress in ipAddressList
    Wscript.Echo "IP: " & Join(ipAddress)
Next

'書き出しファイル作成
Dim outputFile
Set outputFile = fso.OpenTextFile("outputText.txt", 2, True)
