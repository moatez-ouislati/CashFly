package tn.cashfly.interfaces;

import java.sql.SQLException;
import java.util.List;

public interface Service<T> {
    void add(T t) throws SQLException;

    List<T> getAll() throws SQLException;

    void update(T t) throws SQLException;

    void delete(T t) throws SQLException;

    void deleteAll() throws SQLException;
}
