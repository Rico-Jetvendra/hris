<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>HRIS Notification</title>
    </head>

    <body style="margin:0; padding:30px 15px; background:#f4f6f9; font-family:Arial, Helvetica, sans-serif; color:#333;">
        <div style="max-width:700px; margin:auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.08);">
            <!-- Header -->
            <div style="background:linear-gradient(135deg,#0d47a1,#1976d2); padding:30px; color:white;">
                <h1 style="margin:0; font-size:32px;">
                    HRIS Notification System
                </h1>

                <p style="margin-top:10px; font-size:16px; opacity:0.9;">
                    Upcoming Due Date Reminder
                </p>
            </div>

            <!-- Content -->
            <div style="padding:35px;">
                <h2 style="margin-top:0;">
                    Dear ucfirst({{ $data['username']}})
                </h2>

                <p style="font-size:16px; line-height:1.7;">
                    Ini adalah pengingat otomatis mengenai tanggal jatuh tempo yang akan datang dalam sistem HRIS.
                </p>

                <!-- Vehicle Tax -->
                @if($data['vehicleTax']->count() > 0)
                    <div style="margin-top:30px; border:1px solid #dbeafe; border-radius:10px; padding:20px; background:#f8fbff;">
                        <h3 style="margin-top:0; color:#1565c0;">
                            Pengingat Jatuh Tempo Pajak Kendaraan:
                        </h3>

                        <p style="line-height:1.7;">
                            Terdapat {{ $data['vehicleTax']->count() }} kendaraan dengan tanggal jatuh tempo pajak yang akan datang dalam 7 hari ke depan.
                        </p>

                        <table width="100%" cellpadding="12" cellspacing="0" style="border-collapse:collapse; margin-top:15px;">
                            <thead>
                                <tr style="background:#e3f2fd;">
                                    <th align="left" style="border:1px solid #bbdefb;">
                                        KENDARAAN
                                    </th>

                                    <th align="left" style="border:1px solid #bbdefb;">
                                        TGL. JATUH TEMPO PAJAK
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($data['vehicleTax'] as $v)
                                <tr>
                                    <td style="border:1px solid #e0e0e0;">
                                        {{ $v->vehicle_number }}
                                    </td>

                                    <td style="border:1px solid #e0e0e0;">
                                        {{ \Carbon\Carbon::parse($v->vehicle_tax_due)->format('d M Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                @endif

                <!-- Vehicle STNK -->
                @if($data['vehicleReg']->count() > 0)
                    <div style="margin-top:30px; border:1px solid #dbeafe; border-radius:10px; padding:20px; background:#f8fbff;">
                        <h3 style="margin-top:0; color:#1565c0;">
                            Pengingat Jatuh Tempo STNK Kendaraan:
                        </h3>

                        <p style="line-height:1.7;">
                            Terdapat {{ $data['vehicleReg']->count() }} kendaraan dengan tanggal jatuh tempo STNK yang akan datang dalam 7 hari ke depan.
                        </p>

                        <table width="100%" cellpadding="12" cellspacing="0" style="border-collapse:collapse; margin-top:15px;">
                            <thead>
                                <tr style="background:#e3f2fd;">
                                    <th align="left" style="border:1px solid #bbdefb;">
                                        KENDARAAN
                                    </th>

                                    <th align="left" style="border:1px solid #bbdefb;">
                                        TGL. JATUH TEMPO STNK
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($data['vehicleReg'] as $v)
                                <tr>
                                    <td style="border:1px solid #e0e0e0;">
                                        {{ $v->vehicle_number }}
                                    </td>

                                    <td style="border:1px solid #e0e0e0;">
                                        {{ \Carbon\Carbon::parse($v->vehicle_reg_due)->format('d M Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                @endif

                <!-- Employee -->
                @if($data['employee']->count() > 0)
                    <div style="margin-top:30px; border:1px solid #ffe0b2; border-radius:10px; padding:20px; background:#fffaf4;">
                        <h3 style="margin-top:0; color:#ef6c00;">
                            Pengingat Kontrak Karyawan:
                        </h3>

                        <p style="line-height:1.7;">
                            Terdapat {{ $data['employee']->count() }} kontrak karyawan yang akan berakhir dalam 7 hari ke depan.
                        </p>

                        <table width="100%" cellpadding="12" cellspacing="0" style="border-collapse:collapse; margin-top:15px;">

                            <thead>
                                <tr style="background:#fff3e0;">
                                    <th align="left" style="border:1px solid #ffe0b2;">
                                        KARYAWAN
                                    </th>

                                    <th align="left" style="border:1px solid #ffe0b2;">
                                        AKHIR KONTRAK
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($data['employee'] as $e)
                                <tr>
                                    <td style="border:1px solid #e0e0e0;">
                                        {{ $e->employee_name }}
                                    </td>

                                    <td style="border:1px solid #e0e0e0;">
                                        {{ \Carbon\Carbon::parse($e->end_of_contract)->format('d M Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                @endif

                <div style="margin-top:35px;">
                    <p style="line-height:1.7;">
                        Mohon di tinjau kembali dan ambil tindakan yang diperlukan.
                    </p>

                    <p style="margin-top:30px;">
                        Terima kasih.
                    </p>

                    <p style="margin-top:30px; font-weight:bold; color:#1565c0;">
                        HRIS Notification System
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
