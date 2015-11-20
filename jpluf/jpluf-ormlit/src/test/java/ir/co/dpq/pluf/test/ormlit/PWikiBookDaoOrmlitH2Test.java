package ir.co.dpq.pluf.test.ormlit;

import static ir.co.dpq.pluf.test.TestConstant.DATABASE_URL;

import com.j256.ormlite.dao.Dao;
import com.j256.ormlite.dao.DaoManager;
import com.j256.ormlite.jdbc.JdbcConnectionSource;
import com.j256.ormlite.support.ConnectionSource;
import com.j256.ormlite.table.TableUtils;

import ir.co.dpq.pluf.PWikiBookDaoOrmlit;
import ir.co.dpq.pluf.PWikiPageDaoOrmLit;
import ir.co.dpq.pluf.test.wiki.PWikiBookDaoTest;
import ir.co.dpq.pluf.wiki.IPWikiBookDao;
import ir.co.dpq.pluf.wiki.IPWikiPageDao;
import ir.co.dpq.pluf.wiki.PWikiBook;
import ir.co.dpq.pluf.wiki.PWikiPage;

/**
 * 
 * @author maso
 *
 */
public class PWikiBookDaoOrmlitH2Test extends PWikiBookDaoTest {

	PWikiBookDaoOrmlit wikiBookDaoOrmlitJdbc;
	Dao<PWikiBook, Long> wikiBookDao;

	PWikiPageDaoOrmLit wikiPageDaoOrmLit;
	Dao<PWikiPage, Long> wikiPageDao;

	public PWikiBookDaoOrmlitH2Test() throws Exception {
		setupService();
	}

	void setupService() throws Exception {
		ConnectionSource connectionSource = new JdbcConnectionSource(DATABASE_URL);
		setupDatabase(connectionSource);

		wikiBookDaoOrmlitJdbc = new PWikiBookDaoOrmlit();
		wikiBookDaoOrmlitJdbc.setWikiDao(wikiBookDao);
		wikiBookDaoOrmlitJdbc.setWikiPageDao(wikiPageDao);

		wikiPageDaoOrmLit = new PWikiPageDaoOrmLit();
		wikiPageDaoOrmLit.setWikiPageDao(wikiPageDao);
	}

	/**
	 * Setup our database and DAOs
	 */
	void setupDatabase(ConnectionSource connectionSource) throws Exception {
		wikiBookDao = DaoManager.createDao(connectionSource, PWikiBook.class);
		TableUtils.createTableIfNotExists(connectionSource, PWikiBook.class);

		wikiPageDao = DaoManager.createDao(connectionSource, PWikiPage.class);
		TableUtils.createTableIfNotExists(connectionSource, PWikiPage.class);

	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.test.wiki.PWikiBookDaoTest#getWikiBookInstance()
	 */
	@Override
	protected IPWikiBookDao getWikiBookInstance() {
		return wikiBookDaoOrmlitJdbc;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.test.wiki.PWikiBookDaoTest#getWikiPageInstance()
	 */
	@Override
	protected IPWikiPageDao getWikiPageInstance() {
		return wikiPageDaoOrmLit;
	}

}
