/**
 * A class to generate a customized error when something fails
 * @version 1;
 */
class GenerateException {
    public static void printError(String classNameAndMethod, Exception err) {
        String methodStr = classNameAndMethod + " geeft een error:\n";

        System.err.println("\n" + methodStr
            + "  " + err.getMessage() + "\n"
            + buildStackString( err.getStackTrace() ) + "\n"
            + buildCauseString( err.getCause() )
        );
    }

    private static String buildStackString( StackTraceElement[] errStack ) {
        String errStackString = "";

        for (StackTraceElement stackTraceElement : errStack) {
            errStackString += "    " + stackTraceElement + "\n";
        }
        return errStackString;
    }

    private static String buildCauseString (Throwable causeThrow) {
        String causeString = "";
        while (true) {
            if (causeThrow == null) {
                break;
            }

            causeString += causeThrow.getMessage();
            causeThrow = causeThrow.getCause();
        }
        return causeString;
    }
}