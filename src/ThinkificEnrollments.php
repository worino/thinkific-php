<?php


namespace Thinkific;

use Http\Client\Exception;
use stdClass;

class ThinkificEnrollments extends ThinkificResource
{

    /**
     * Lists Enrollments.
     *
     * @see    https://developers.thinkific.com/api/api-documentation/
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function list(array $options = [])
    {
        return $this->client->get('enrollments', $options);
    }

    /**
     * Creates an Enrollment.
     *
     * @see    https://developers.thinkific.com/api/api-documentation/
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function create(array $options)
    {
        return $this->client->post('enrollments', $options);
    }

    /**
     * Gets a single enrollment by Enrollment ID
     *
     * @see    https://developers.thinkific.com/api/api-documentation/
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function get($id)
    {
        return $this->client->get('enrollments/'.$id);
    }

    /**
     * Updates an Enrollment.
     *
     * @see    https://developers.thinkific.com/api/api-documentation/
     * @param string $id
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function update($id, array $options)
    {
        return $this->client->put('enrollments/'.$id, $options);
    }

    /**
     * Expires an Enrollment.
     * Wrapper for update which sets expiry date to today at midnight
     *
     * @see    https://developers.thinkific.com/api/api-documentation/
     * @param string $id Enrollment ID
     * @param string $expiry_date Expiry Date in ISO 8601 Format
     * @return stdClass
     * @throws Exception
     */
    public function expire($id, $expiry_date = false)
    {
        if(!$expiry_date) {

            $expiry_date = date('c', strtotime('today'));
        }

        return $this->update($id, ['expiry_date' => $expiry_date]);
    }

    /**
     * Finds Enrollments by given params
     * Defaults to a search by User ID
     * See Thinkific Docs for more information & filter options
     *
     * @see    https://developers.thinkific.com/api/api-documentation/
     * @param string $value The value to search by e.g. User Thinkific ID
     * @param string $query The query param e.g. 'user_id', 'email' etc
     * @param array $options Additional options e.g. Page, Limit etc
     * @param bool $single Return a single result, useful for finding enrollments for a given user
     * @return stdClass
     */
    public function findBy($value, $query = 'user_id', $options = [], $single = false)
    {
        $query = "query[$query]";

        $options = array_merge([$query => $value], $options);

        if($single) {

            $options = array_merge(
                $options,
                ['page' => 1, 'limit' => 1]
            );
        }

        $result = $this->client->get('enrollments', $options);

        if($single && isset($result->items)) {

            return reset($result->items);
        }

       return $result;

    }

}
