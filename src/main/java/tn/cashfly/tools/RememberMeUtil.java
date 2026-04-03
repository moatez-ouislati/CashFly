package tn.cashfly.tools;

import java.security.SecureRandom;
import java.time.Instant;
import java.util.Base64;
import java.util.prefs.Preferences;

public class RememberMeUtil {
    private static final String PREF_NODE = "tn.cashfly.auth";
    private static final String KEY_TOKEN = "token";
    private static final String KEY_USERID = "userId";
    private static final String KEY_EXP = "exp";

    public static class TokenData {
        public final int userId;
        public final long exp;
        public TokenData(int userId, long exp) { this.userId = userId; this.exp = exp; }
    }

    public static void save(int userId, long ttlMillis) {
        byte[] bytes = new byte[32];
        new SecureRandom().nextBytes(bytes);
        String token = Base64.getUrlEncoder().withoutPadding().encodeToString(bytes);
        long exp = Instant.now().toEpochMilli() + ttlMillis;

        Preferences p = Preferences.userRoot().node(PREF_NODE);
        p.put(KEY_TOKEN, token);
        p.putInt(KEY_USERID, userId);
        p.putLong(KEY_EXP, exp);
    }

    public static TokenData loadIfValid() {
        Preferences p = Preferences.userRoot().node(PREF_NODE);
        String token = p.get(KEY_TOKEN, null);
        int userId = p.getInt(KEY_USERID, -1);
        long exp = p.getLong(KEY_EXP, -1);
        if (token == null || userId <= 0 || exp <= 0) return null;
        if (Instant.now().toEpochMilli() > exp) {
            clear();
            return null;
        }
        return new TokenData(userId, exp);
    }

    public static void clear() {
        Preferences p = Preferences.userRoot().node(PREF_NODE);
        p.remove(KEY_TOKEN);
        p.remove(KEY_USERID);
        p.remove(KEY_EXP);
    }
}
