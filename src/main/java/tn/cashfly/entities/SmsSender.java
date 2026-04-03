package tn.cashfly.entities;

import com.twilio.Twilio;
import com.twilio.rest.api.v2010.account.Message;
import com.twilio.type.PhoneNumber;

public class SmsSender {

    // It's recommended to use environment variables for these secrets
    public static final String ACCOUNT_SID = System.getenv("TWILIO_ACCOUNT_SID") != null ? System.getenv("TWILIO_ACCOUNT_SID") : "YOUR_TWILIO_ACCOUNT_SID";
    public static final String AUTH_TOKEN  = System.getenv("TWILIO_AUTH_TOKEN") != null ? System.getenv("TWILIO_AUTH_TOKEN") : "YOUR_TWILIO_AUTH_TOKEN";

    // رقم Twilio Trial
    private static final String FROM_TWILIO = System.getenv("TWILIO_PHONE_NUMBER") != null ? System.getenv("TWILIO_PHONE_NUMBER") : "+10000000000";

    static {
        Twilio.init(ACCOUNT_SID, AUTH_TOKEN);
    }

    public static void sendSms(String to, String body) {
        try {
            Message.creator(
                    new PhoneNumber(to),
                    new PhoneNumber(FROM_TWILIO),
                    body
            ).create();

            System.out.println("✅ SMS SENT TO " + to);

        } catch (Exception e) {
            System.err.println("❌ SMS FAILED");
            e.printStackTrace();
        }
    }
}
