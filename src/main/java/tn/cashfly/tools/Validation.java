package tn.cashfly.tools;

import java.util.regex.Pattern;

public class Validation {
    private static final Pattern EMAIL = Pattern.compile("^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+$");
    private static final Pattern CIN = Pattern.compile("^[01][0-9]{7}$");
    private static final Pattern PHONE8 = Pattern.compile("^[0-9]{8}$");

    public static boolean isEmail(String s) { return s != null && EMAIL.matcher(s).matches(); }
    public static boolean isCIN(String s) { return s != null && CIN.matcher(s).matches(); }
    public static boolean isPhone8(String s) { return s != null && PHONE8.matcher(s).matches(); }
    public static boolean strongPassword(String p) {
        if (p == null || p.length() < 8) return false;
        boolean up=false, low=false, dig=false, sym=false;
        for (char c : p.toCharArray()) {
            if (Character.isUpperCase(c)) up = true;
            else if (Character.isLowerCase(c)) low = true;
            else if (Character.isDigit(c)) dig = true;
            else sym = true;
        }
        return up && low && dig && sym;
    }
}
