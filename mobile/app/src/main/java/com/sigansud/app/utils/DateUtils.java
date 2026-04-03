package com.sigansud.app.utils;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

/**
 * Utilitaire de conversion et formatage des dates.
 */
public class DateUtils {

    // Formats API (ISO 8601)
    public static final String FORMAT_API_DATE = "yyyy-MM-dd";
    public static final String FORMAT_API_DATETIME = "yyyy-MM-dd'T'HH:mm:ss";
    public static final String FORMAT_API_TIME = "HH:mm";

    // Formats affichage
    public static final String FORMAT_DISPLAY_DATE = "dd/MM/yyyy";
    public static final String FORMAT_DISPLAY_LONG = "dd MMMM yyyy";
    public static final String FORMAT_DISPLAY_DATETIME = "dd/MM/yyyy à HH:mm";

    private static final SimpleDateFormat apiDateFmt =
            new SimpleDateFormat(FORMAT_API_DATE, Locale.getDefault());
    private static final SimpleDateFormat displayDateFmt =
            new SimpleDateFormat(FORMAT_DISPLAY_DATE, Locale.getDefault());
    private static final SimpleDateFormat displayLongFmt =
            new SimpleDateFormat(FORMAT_DISPLAY_LONG, Locale.FRENCH);
    private static final SimpleDateFormat displayDateTimeFmt =
            new SimpleDateFormat(FORMAT_DISPLAY_DATETIME, Locale.FRENCH);

    /** Retourne la date du jour au format API (yyyy-MM-dd) */
    public static String todayApi() {
        return apiDateFmt.format(new Date());
    }

    /** Convertit une date API (yyyy-MM-dd) en affichage lisible (dd/MM/yyyy) */
    public static String apiToDisplay(String apiDate) {
        if (apiDate == null || apiDate.isEmpty()) return "";
        try {
            Date d = apiDateFmt.parse(apiDate);
            return d != null ? displayDateFmt.format(d) : apiDate;
        } catch (ParseException e) {
            return apiDate;
        }
    }

    /** Convertit une date API en format long français (ex: "13 mars 2026") */
    public static String apiToDisplayLong(String apiDate) {
        if (apiDate == null || apiDate.isEmpty()) return "";
        try {
            Date d = apiDateFmt.parse(apiDate);
            return d != null ? displayLongFmt.format(d) : apiDate;
        } catch (ParseException e) {
            return apiDate;
        }
    }

    /** Formate date + heure pour l'affichage */
    public static String formatDateTime(String date, String heure) {
        if (date == null) return "";
        String d = apiToDisplay(date);
        if (heure != null && !heure.isEmpty()) {
            return d + " à " + heure;
        }
        return d;
    }
}