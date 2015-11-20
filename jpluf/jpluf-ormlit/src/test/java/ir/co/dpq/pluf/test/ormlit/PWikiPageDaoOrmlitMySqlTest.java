package ir.co.dpq.pluf.test.ormlit;

import static ir.co.dpq.pluf.test.TestConstant.DATABASE_URL_MYSQL;

import com.j256.ormlite.jdbc.JdbcConnectionSource;
import com.j256.ormlite.support.ConnectionSource;

import ir.co.dpq.pluf.PWikiPageDaoOrmLit;

/**
 * 
 * @author maso
 *
 */
public class PWikiPageDaoOrmlitMySqlTest extends PWikiPageDaoOrmlitH2Test {

	public PWikiPageDaoOrmlitMySqlTest() throws Exception {
		super();
	}

	void setupService() throws Exception {
		ConnectionSource connectionSource = new JdbcConnectionSource(DATABASE_URL_MYSQL);
		setupDatabase(connectionSource);

		wikiPageDao = new PWikiPageDaoOrmLit();
		wikiPageDao.setWikiPageDao(wikiDao);
	}
}
