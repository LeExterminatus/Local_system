CREATE FUNCTION public.getuserauthinfo(loginusr character varying) RETURNS TABLE(pass character varying, id integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
  Return query
  SELECT Password AS Pass, IdUser AS Id FROM AuthorizationInfo WHERE Login = LoginUsr; --INTO PasswordUsr, IdUsr;
END;
$$;