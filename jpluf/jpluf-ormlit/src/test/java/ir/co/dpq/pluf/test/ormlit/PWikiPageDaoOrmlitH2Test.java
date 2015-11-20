package ir.co.dpq.pluf.test.ormlit;

import static ir.co.dpq.pluf.test.TestConstant.DATABASE_URL;

import com.j256.ormlite.dao.Dao;
import com.j256.ormlite.dao.DaoManager;
import com.j256.ormlite.jdbc.JdbcConnectionSource;
import com.j256.ormlite.support.ConnectionSource;
import com.j256.ormlite.table.TableUtils;

import ir.co.dpq.pluf.PWikiPageDaoOrmLit;
import ir.co.dpq.pluf.test.wiki.PWikiPageDaoTest;
import ir.co.dpq.pluf.wiki.IPWikiPageDao;
import ir.co.dpq.pluf.wiki.PWikiPage;

/**
 * 
 * @author maso
 *
 */
public class PWikiPageDaoOrmlitH2Test extends PWikiPageDaoTest {

	PWikiPageDaoOrmLit wikiPageDao;
	Dao<PWikiPage, Long> wikiDao;

	public PWikiPageDaoOrmlitH2Test() throws Exception {
		setupService();
	}

	void setupService() throws Exception {
		ConnectionSource connectionSource = new JdbcConnectionSource(DATABASE_URL);
		setupDatabase(connectionSource);

		wikiPageDao = new PWikiPageDaoOrmLit();
		wikiPageDao.setWikiPageDao(wikiDao);
	}

	/**
	 * Setup our database and DAOs
	 */
	void setupDatabase(ConnectionSource connectionSource) throws Exception {
		wikiDao = DaoManager.createDao(connectionSource, PWikiPage.class);

		// if you need to create the table
		// TableUtils.createTable(connectionSource, Account.class);
		TableUtils.createTableIfNotExists(connectionSource, PWikiPage.class);
	}

	@Override
	protected IPWikiPageDao getWikiPageInstance() {
		return wikiPageDao;
	}

}
