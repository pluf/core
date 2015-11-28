package ir.co.dpq.pluf.saas;

public interface IPTenantDao {

	PTenant current();

	PTenant setCurrent(Long id);

	PTenant get(Long id);
}
