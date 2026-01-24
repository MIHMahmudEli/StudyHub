<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Achievement Certificate - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Dancing+Script:wght@400;500&family=Allura&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f1ea; margin: 0; padding: 40px 20px; font-family: 'Times New Roman', Georgia, serif; display: flex; flex-direction: column; align-items: center; }
        
        /* PREMIUM A4 PDF TEMPLATE */
        .cert-container { 
            width: 1122px; /* Standard A4 Landscape Pixels @ 96DPI */
            height: 794px;
            background: #ffffff;
            position: relative;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
            overflow: hidden;
            box-sizing: border-box;
        }
        
        .parchment-bg { position: absolute; inset: 0; background: #fff9f0; z-index: 1; }
        .border-outer { position: absolute; inset: 25px; border: 15px solid #c5a059; border-image: linear-gradient(to bottom, #d4af37, #b8860b, #d4af37) 1; box-sizing: border-box; z-index: 2; }
        .border-inner { position: absolute; inset: 45px; border: 2px solid #b8860b; box-sizing: border-box; z-index: 3; }
        
        .corner { position: absolute; width: 60px; height: 60px; border-color: #b8860b; border-style: solid; z-index: 4; border-width: 0; }
        .c-tl { top: 35px; left: 35px; border-top-width: 5px; border-left-width: 5px; }
        .c-tr { top: 35px; right: 35px; border-top-width: 5px; border-right-width: 5px; }
        .c-bl { bottom: 35px; left: 35px; border-bottom-width: 5px; border-left-width: 5px; }
        .c-br { bottom: 35px; right: 35px; border-bottom-width: 5px; border-right-width: 5px; }

        .cert-content {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 55px 80px 60px 80px;
            text-align: center;
        }

        .download-action {
            margin-top: 40px;
            display: flex;
            gap: 20px;
            z-index: 100;
        }
        .btn {
            background: #1e293b;
            color: #fff;
            padding: 15px 35px;
            border-radius: 2px;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            border: 1px solid #d4af37;
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .btn:hover { background: #000; transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0,0,0,0.3); }
        .btn-outline { background: #fff; color: #1e293b; border: 2px solid #1e293b; }
        .btn-outline:hover { background: #fdfbf7; }

        @media print {
            .download-action { display: none; }
            body { padding: 0; background: #fff; }
            .cert-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="cert-container" id="printableCert">
        <div class="parchment-bg"></div>
        <div class="border-outer"></div>
        <div class="border-inner"></div>
        <div class="corner c-tl"></div>
        <div class="corner c-tr"></div>
        <div class="corner c-bl"></div>
        <div class="corner c-br"></div>

        <div class="cert-content">
            <!-- Header Section -->
            <div style="width: 100%;">
                <div style="margin-bottom: 8px;">
                    <span style="font-size: 24px; font-weight: bold; color: #1e293b; letter-spacing: 5px; text-transform: uppercase; border-bottom: 2px solid #1e293b; padding-bottom: 4px;">StudyHub</span>
                </div>
                <div style="color: #b8860b; font-size: 12px; letter-spacing: 6px; text-transform: uppercase; font-weight: bold; margin-bottom: 15px;">Certificate of Achievement</div>
            </div>

            <!-- Main Content Section -->
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%;">
                <h1 style="color: #1e293b; font-size: 72px; margin: 0 0 8px 0; font-weight: normal; letter-spacing: 10px; text-transform: uppercase;">Certificate</h1>
                <div style="width: 350px; height: 2px; background: linear-gradient(to right, transparent, #b8860b, transparent); margin: 0 auto 8px auto;"></div>
                <h2 style="color: #475569; font-size: 28px; margin: 0 0 30px 0; font-weight: normal; font-style: italic;">of Professional Excellence</h2>
                
                <p style="color: #64748b; font-size: 18px; margin-bottom: 12px; font-style: italic;">This prestigious award is proudly presented to</p>
                
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #1e293b; font-size: 48px; margin: 0; font-weight: bold; border-bottom: 3px solid #1e293b; display: inline-block; padding-bottom: 4px; min-width: 450px;">
                        <?php echo htmlspecialchars($user['name']); ?>
                    </h3>
                </div>
                
                <!-- Achievement Details -->
                <div style="margin-bottom: 20px; max-width: 750px; background: rgba(184, 134, 11, 0.05); padding: 20px 30px; border-radius: 8px; border: 1px solid rgba(184, 134, 11, 0.2);">
                    <p style="color: #b8860b; font-size: 32px; font-weight: bold; margin: 0 0 8px 0; letter-spacing: 2px;">
                        <?php 
                        $rankSuffix = ($rank == 1) ? "st" : (($rank == 2) ? "nd" : "rd");
                        echo $rank . $rankSuffix . " POSITION"; 
                        ?> 
                        <?php echo ($rank == 1) ? "🥇" : (($rank == 2) ? "🥈" : "🥉"); ?>
                    </p>
                    <p style="color: #1e293b; font-size: 20px; font-weight: bold; margin: 0 0 12px 0; letter-spacing: 1px;">
                        <?php echo strtoupper($type === 'student' ? "Top Student" : "Top Contributor"); ?>
                    </p>
                    <p style="color: #475569; font-size: 15px; font-weight: 600; line-height: 1.5; margin: 0;">
                        <?php echo ($type === 'student') 
                            ? "For demonstrating exceptional academic commitment and outstanding learning performance on the StudyHub platform."
                            : "For significant knowledge sharing contributions and support to the global academic community."; ?>
                    </p>
                </div>
            </div>

            <!-- Footer Section -->
            <div style="width: 100%; display: flex; justify-content: space-between; align-items: flex-end;">
                <div style="width: 280px; text-align: left;">
                    <div style="border-top: 2px solid #1e293b; padding-top: 8px;">
                        <div style="font-size: 14px; color: #1e293b; font-weight: bold; font-style: italic; margin-bottom: 2px;">Mohsin Ibna Hossain</div>
                        <div style="font-size: 13px; color: #1e293b; font-weight: 600;">Academic Director</div>
                        <div style="font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">StudyHub Authority</div>
                    </div>
                </div>
                
                <div style="width: 120px; text-align: center;">
                    <div style="font-size: 56px; color: #d4af37; line-height: 1;">🏅</div>
                    <div style="font-size: 9px; color: #b8860b; font-weight: bold; margin-top: 4px;">VERIFIED</div>
                </div>

                <div style="width: 280px; text-align: right;">
                    <div style="border-top: 2px solid #1e293b; padding-top: 8px;">
                        <div style="font-size: 16px; color: #1e293b; font-weight: bold;"><?php echo date('F d, Y'); ?></div>
                        <div style="font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Issue Date</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 40px; text-align: center;">
        <p style="color: #64748b; font-style: italic; font-size: 14px;">This is a verified digital achievement record issued by StudyHub Platform. An official PDF copy has been delivered to your registered email.</p>
    </div>
</body>
</html>
