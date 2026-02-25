package tn.cashfly.exceptions;

public class CashFlyException extends RuntimeException {
    public CashFlyException(String message) {
        super(message);
    }

    public CashFlyException(String message, Throwable cause) {
        super(message, cause);
    }
}
