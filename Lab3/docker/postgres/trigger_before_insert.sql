create or replace function check_exist_feedback() returns trigger as
$$
declare
    last_feedback timestamp with time zone;
begin
    last_feedback = (select created_at
                     from feedback
                     where email = new.email
                     order by created_at desc
                     limit 1);

    if last_feedback is null then
        return new;
    end if;

    if new.created_at is null then
        new.created_at = now();
    end if;

    if new.created_at - last_feedback < interval '1 HOURS' then
        raise exception 'Вы еще не можете оставить заявку! Подождите еще %!',
            to_char(interval '1 HOURS' - (new.created_at - last_feedback), 'MI:SS');
    end if;

    return new;
end;
$$ language plpgsql;

create trigger check_exist_feedback_trigger
    before insert
    on feedback
    for each row
execute procedure check_exist_feedback();