package ir.co.dpq.pluf.test.ormlit;

import static ir.co.dpq.pluf.test.TestConstant.DATABASE_URL;

import com.j256.ormlite.dao.Dao;
import com.j256.ormlite.dao.DaoManager;
import com.j256.ormlite.jdbc.JdbcConnectionSource;
import com.j256.ormlite.support.ConnectionSource;
import com.j256.ormlite.table.TableUtils;

import ir.co.dpq.pluf.PWikiBookDaoOrmlit;
import ir.co.dpq.pluf.test.wiki.PWikiBookDaoTest;
import ir.co.dpq.pluf.wiki.IPWikiBookDao;
import ir.co.dpq.pluf.wiki.PWikiBook;

public class PWikiDaoOrmlitJDBCTest extends PWikiBookDaoTest {

	PWikiBookDaoOrmlit wikiBookDaoOrmlitJdbc;
	Dao<PWikiBook, Long> wikiDao;

	public PWikiDaoOrmlitJDBCTest() throws Exception {
		ConnectionSource connectionSource = new JdbcConnectionSource(DATABASE_URL);
		setupDatabase(connectionSource);
		setupService();
	}

	void setupService() {
		wikiBookDaoOrmlitJdbc = new PWikiBookDaoOrmlit();
		wikiBookDaoOrmlitJdbc.setWikiDao(wikiDao);
	}

	/**
	 * Setup our database and DAOs
	 */
	void setupDatabase(ConnectionSource connectionSource) throws Exception {
		wikiDao = DaoManager.createDao(connectionSource, PWikiBook.class);

		// if you need to create the table
		// TableUtils.createTable(connectionSource, Account.class);
		TableUtils.createTableIfNotExists(connectionSource, PWikiBook.class);
	}

	@Override
	protected IPWikiBookDao getWikiBookInstance() {
		return wikiBookDaoOrmlitJdbc;
	}

}
